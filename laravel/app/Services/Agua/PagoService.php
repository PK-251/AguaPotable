<?php

namespace App\Services\Agua;

use App\Events\Pagos\PagoRegistrado;
use App\Models\MultaUsuario;
use App\Models\PadronUsuario;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagoService
{
    public function registrarCobro(
        PadronUsuario $padron,
        User $operador,
        string $periodo,
        string $estado,
    ): Pago {
        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $periodo)) {
            throw ValidationException::withMessages([
                'periodo' => 'El periodo debe tener formato YYYY-MM con mes válido.',
            ]);
        }

        if (! in_array($estado, ['pagado', 'pendiente'], true)) {
            throw ValidationException::withMessages([
                'estado' => 'Estado de cobro inválido.',
            ]);
        }

        return DB::transaction(function () use ($padron, $operador, $periodo, $estado): Pago {
            $padron->load(['tarifa']);
            if ($padron->tarifa === null) {
                throw ValidationException::withMessages([
                    'padron_usuario_id' => 'El usuario del padrón no tiene una tarifa asignada.',
                ]);
            }

            $tarifaMonto = $this->normalizarDosDecimales((string) $padron->tarifa->monto);

            if ($estado === 'pendiente') {
                return $this->sincronizarPendienteDelPeriodo(
                    $padron,
                    $operador,
                    $periodo,
                    $tarifaMonto,
                );
            }

            return $this->liquidarPagado(
                $padron,
                $operador,
                $periodo,
                $tarifaMonto,
            );
        });
    }

    private function sincronizarPendienteDelPeriodo(
        PadronUsuario $padron,
        User $operador,
        string $periodo,
        string $tarifaMonto,
    ): Pago {
        $existente = Pago::query()
            ->where('padron_usuario_id', $padron->id)
            ->where('periodo', $periodo)
            ->lockForUpdate()
            ->first();

        if ($existente && $existente->estado === 'pagado') {
            throw ValidationException::withMessages([
                'periodo' => 'Este periodo ya figura como pagado.',
            ]);
        }

        if ($existente && $existente->estado === 'pendiente') {
            return $existente;
        }

        return Pago::query()->create([
            'padron_usuario_id' => $padron->id,
            'user_id' => $operador->id,
            'periodo' => $periodo,
            'monto_cuota' => $tarifaMonto,
            'monto_deuda' => '0.00',
            'monto_multas' => '0.00',
            'monto_total' => $tarifaMonto,
            'numero_serie' => null,
            'estado' => 'pendiente',
            'pdf_path' => null,
        ]);
    }

    private function liquidarPagado(
        PadronUsuario $padron,
        User $operador,
        string $periodo,
        string $tarifaMonto,
    ): Pago {
        $pagoPeriodo = Pago::query()
            ->where('padron_usuario_id', $padron->id)
            ->where('periodo', $periodo)
            ->lockForUpdate()
            ->first();

        if ($pagoPeriodo && $pagoPeriodo->estado === 'pagado') {
            throw ValidationException::withMessages([
                'periodo' => 'Este periodo ya fue cobrado como pagado.',
            ]);
        }

        $pagosPendientes = Pago::query()
            ->where('padron_usuario_id', $padron->id)
            ->where('estado', 'pendiente')
            ->lockForUpdate()
            ->get();

        $multas = MultaUsuario::query()
            ->where('padron_usuario_id', $padron->id)
            ->where('pagada', false)
            ->lockForUpdate()
            ->get();

        $montoMultas = $this->sumarColeccionMontos($multas->pluck('monto')->all());

        $montoCuota = '0.00';
        if ($pagoPeriodo === null) {
            $montoCuota = $tarifaMonto;
        } else {
            $montoCuota = $this->normalizarDosDecimales((string) $pagoPeriodo->monto_cuota);
        }

        $montoDeuda = '0.00';
        foreach ($pagosPendientes as $pendiente) {
            if ($pagoPeriodo !== null && (int) $pendiente->id === (int) $pagoPeriodo->id) {
                continue;
            }
            $montoDeuda = bcadd($montoDeuda, $this->normalizarDosDecimales((string) $pendiente->monto_total), 2);
        }

        $montoTotal = bcadd(bcadd($montoCuota, $montoDeuda, 2), $montoMultas, 2);

        foreach ($pagosPendientes as $pendiente) {
            if ($pagoPeriodo !== null && (int) $pendiente->id === (int) $pagoPeriodo->id) {
                continue;
            }
            $pendiente->update(['estado' => 'pagado']);
        }

        foreach ($multas as $multa) {
            $multa->update(['pagada' => true]);
        }

        if ($pagoPeriodo) {
            $pagoPeriodo->fill([
                'user_id' => $operador->id,
                'monto_cuota' => $montoCuota,
                'monto_deuda' => $montoDeuda,
                'monto_multas' => $montoMultas,
                'monto_total' => $montoTotal,
                'estado' => 'pagado',
            ]);
            $pagoPeriodo->save();
            $pagoPeriodo->refresh();
            $final = $pagoPeriodo;
        } else {
            $final = Pago::query()->create([
                'padron_usuario_id' => $padron->id,
                'user_id' => $operador->id,
                'periodo' => $periodo,
                'monto_cuota' => $montoCuota,
                'monto_deuda' => $montoDeuda,
                'monto_multas' => $montoMultas,
                'monto_total' => $montoTotal,
                'numero_serie' => null,
                'estado' => 'pagado',
                'pdf_path' => null,
            ]);
        }

        DB::afterCommit(static function () use ($final): void {
            PagoRegistrado::dispatch($final);
        });

        return $final;
    }

    /**
     * @param  list<mixed>  $montos
     */
    private function sumarColeccionMontos(array $montos): string
    {
        $suma = '0.00';
        foreach ($montos as $monto) {
            $suma = bcadd($suma, $this->normalizarDosDecimales((string) $monto), 2);
        }

        return $suma;
    }

    private function normalizarDosDecimales(string $valor): string
    {
        return number_format((float) $valor, 2, '.', '');
    }
}
