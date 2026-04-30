<?php

namespace App\Services\Agua;

use App\Models\Tarifa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TarifaService
{
    /**
     * @param  array{q?: string|null}  $filtros
     */
    public function paginar(array $filtros = [], int $porPagina = 15): LengthAwarePaginator
    {
        $consulta = Tarifa::query()
            ->orderByDesc('vigente_desde')
            ->orderBy('nombre');

        $termino = isset($filtros['q']) ? trim((string) $filtros['q']) : '';
        if ($termino !== '') {
            $like = '%'.$termino.'%';
            $consulta->where(function (Builder $q) use ($like): void {
                $q->where('nombre', 'like', $like)
                    ->orWhere('descripcion', 'like', $like);
            });
        }

        return $consulta->paginate($porPagina)->withQueryString();
    }

    /**
     * @param  array{nombre: string, monto: float|int|string, descripcion: ?string, vigente_desde: string}  $datos
     */
    public function crear(array $datos): Tarifa
    {
        return Tarifa::query()->create([
            'nombre' => $datos['nombre'],
            'monto' => $datos['monto'],
            'descripcion' => $datos['descripcion'] ?? null,
            'vigente_desde' => $datos['vigente_desde'],
        ]);
    }

    /**
     * @param  array{nombre: string, monto: float|int|string, descripcion: ?string, vigente_desde: string}  $datos
     */
    public function actualizar(Tarifa $tarifa, array $datos): Tarifa
    {
        $tarifa->fill([
            'nombre' => $datos['nombre'],
            'monto' => $datos['monto'],
            'descripcion' => $datos['descripcion'] ?? null,
            'vigente_desde' => $datos['vigente_desde'],
        ]);
        $tarifa->save();

        return $tarifa->fresh();
    }
}
