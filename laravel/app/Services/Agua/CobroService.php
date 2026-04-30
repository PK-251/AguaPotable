<?php

namespace App\Services\Agua;

use App\Models\MultaUsuario;
use App\Models\PadronUsuario;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class CobroService
{
    /**
     * @return Collection<int, PadronUsuario>
     */
    public function buscarResidentes(?string $termino, int $limite = 25): Collection
    {
        $consulta = PadronUsuario::query()
            ->with(['tarifa:id,nombre'])
            ->orderBy('codigo');

        $termino = $termino !== null ? trim($termino) : '';
        if ($termino !== '') {
            $like = '%'.$termino.'%';
            $consulta->where(function ($q) use ($like): void {
                $q->where('codigo', 'like', $like)
                    ->orWhere('nombre', 'like', $like)
                    ->orWhere('apellido', 'like', $like)
                    ->orWhere('direccion', 'like', $like);
            });
        }

        return $consulta->limit($limite)->get();
    }

    public function construirResumen(PadronUsuario $padron, ?string $periodo = null): CobroResumen
    {
        $periodo ??= Carbon::now()->format('Y-m');
        $padron->loadMissing(['tarifa']);

        $pagoPeriodo = Pago::query()
            ->where('padron_usuario_id', $padron->id)
            ->where('periodo', $periodo)
            ->first();

        $pagosPendientes = Pago::query()
            ->where('padron_usuario_id', $padron->id)
            ->where('estado', 'pendiente')
            ->orderBy('periodo')
            ->get();

        $multas = MultaUsuario::query()
            ->where('padron_usuario_id', $padron->id)
            ->where('pagada', false)
            ->with(['multa:id,nombre'])
            ->orderBy('mes')
            ->get();

        $lineas = [];
        $total = '0.00';

        foreach ($pagosPendientes as $pago) {
            $importe = $this->normalizarDecimal((string) $pago->monto_total);
            $lineas[] = [
                'concepto' => 'Cobro pendiente — periodo '.$pago->periodo,
                'monto' => $importe,
                'tone' => null,
                'badge' => 'Pendiente',
            ];
            $total = bcadd($total, $importe, 2);
        }

        foreach ($multas as $multa) {
            $nombreMulta = $multa->multa?->nombre ?? 'Multa';
            $importe = $this->normalizarDecimal((string) $multa->monto);
            $lineas[] = [
                'concepto' => $nombreMulta.' ('.$multa->mes.')',
                'monto' => $importe,
                'tone' => 'warning',
                'badge' => 'Multa',
            ];
            $total = bcadd($total, $importe, 2);
        }

        if ($pagoPeriodo === null && $padron->tarifa !== null) {
            $cuota = $this->normalizarDecimal((string) $padron->tarifa->monto);
            $lineas[] = [
                'concepto' => 'Cuota sugerida — periodo '.$periodo,
                'monto' => $cuota,
                'tone' => 'primary',
                'badge' => 'Cuota',
            ];
            $total = bcadd($total, $cuota, 2);
        }

        $nombreCompleto = trim($padron->nombre.' '.$padron->apellido);

        return new CobroResumen(
            padronId: $padron->id,
            codigo: $padron->codigo,
            nombreCompleto: $nombreCompleto !== '' ? $nombreCompleto : $padron->codigo,
            direccion: $padron->direccion,
            estado: $padron->estado,
            tarifaNombre: $padron->tarifa?->nombre,
            tarifaMonto: $padron->tarifa !== null ? $this->normalizarDecimal((string) $padron->tarifa->monto) : null,
            periodo: $periodo,
            lineas: $lineas,
            total: $total,
            pagosPendientesFilas: $pagosPendientes->map(static fn (Pago $p): array => [
                'id' => $p->id,
                'periodo' => $p->periodo,
                'monto_total' => (string) $p->monto_total,
                'estado' => $p->estado,
            ])->all(),
            multasImpagasFilas: $multas->map(static fn (MultaUsuario $m): array => [
                'id' => $m->id,
                'mes' => $m->mes,
                'monto' => (string) $m->monto,
                'nombre' => $m->multa?->nombre,
            ])->all(),
            existePagadoParaPeriodo: $pagoPeriodo !== null && $pagoPeriodo->estado === 'pagado',
            pagoPeriodoId: $pagoPeriodo?->id,
            pagoPeriodoEstado: $pagoPeriodo?->estado,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Pago>
     */
    public function ultimosPagosRegistrados(int $limite = 12)
    {
        return Pago::query()
            ->with(['padronUsuario:id,codigo,nombre,apellido'])
            ->orderByDesc('created_at')
            ->limit($limite)
            ->get();
    }

    private function normalizarDecimal(string $valor): string
    {
        return number_format((float) $valor, 2, '.', '');
    }
}
