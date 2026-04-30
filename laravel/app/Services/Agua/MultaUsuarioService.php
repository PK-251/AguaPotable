<?php

namespace App\Services\Agua;

use App\Models\Multa;
use App\Models\MultaUsuario;
use App\Models\PadronUsuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class MultaUsuarioService
{
    /**
     * @param  array{
     *     q?: string|null,
     *     pagada?: string|null,
     *     multa_id?: int|string|null,
     *     padron_usuario_id?: int|string|null
     * }  $filtros
     */
    public function paginarAsignaciones(array $filtros = [], int $porPagina = 20): LengthAwarePaginator
    {
        $consulta = MultaUsuario::query()
            ->with([
                'padronUsuario:id,codigo,nombre,apellido',
                'multa:id,nombre,monto,activa',
            ])
            ->orderByDesc('created_at');

        $termino = isset($filtros['q']) ? trim((string) $filtros['q']) : '';
        if ($termino !== '') {
            $like = '%'.$termino.'%';
            $consulta->whereHas('padronUsuario', function (Builder $q) use ($like): void {
                $q->where('codigo', 'like', $like)
                    ->orWhere('nombre', 'like', $like)
                    ->orWhere('apellido', 'like', $like)
                    ->orWhere('direccion', 'like', $like);
            });
        }

        $pagada = $filtros['pagada'] ?? null;
        if ($pagada === '1' || $pagada === '0') {
            $consulta->where('pagada', (bool) (int) $pagada);
        }

        $multaId = $filtros['multa_id'] ?? null;
        if ($multaId !== null && $multaId !== '') {
            $consulta->where('multa_id', (int) $multaId);
        }

        $padronId = $filtros['padron_usuario_id'] ?? null;
        if ($padronId !== null && $padronId !== '') {
            $consulta->where('padron_usuario_id', (int) $padronId);
        }

        return $consulta->paginate($porPagina)->withQueryString();
    }

    /**
     * @param  array{padron_usuario_id: int, multa_id: int, mes: string, monto?: float|int|string|null}  $datos
     */
    public function asignar(array $datos): MultaUsuario
    {
        $multa = Multa::query()->findOrFail($datos['multa_id']);

        if (! $multa->activa) {
            throw ValidationException::withMessages([
                'multa_id' => 'La multa seleccionada está inactiva y no puede asignarse.',
            ]);
        }

        PadronUsuario::query()->findOrFail($datos['padron_usuario_id']);

        $duplicado = MultaUsuario::query()
            ->where('padron_usuario_id', $datos['padron_usuario_id'])
            ->where('multa_id', $datos['multa_id'])
            ->where('mes', $datos['mes'])
            ->exists();

        if ($duplicado) {
            throw ValidationException::withMessages([
                'mes' => 'Este usuario ya tiene la misma multa registrada para el mes indicado.',
            ]);
        }

        $monto = $datos['monto'] ?? null;
        if ($monto === null || $monto === '') {
            $monto = $multa->monto;
        }

        return MultaUsuario::query()->create([
            'padron_usuario_id' => $datos['padron_usuario_id'],
            'multa_id' => $datos['multa_id'],
            'mes' => $datos['mes'],
            'monto' => $monto,
            'pagada' => false,
        ]);
    }
}
