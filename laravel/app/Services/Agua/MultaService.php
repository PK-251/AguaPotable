<?php

namespace App\Services\Agua;

use App\Models\Multa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MultaService
{
    /**
     * @param  array{q?: string|null, activa?: string|null}  $filtros
     */
    public function paginarCatalogo(array $filtros = [], int $porPagina = 15): LengthAwarePaginator
    {
        $consulta = Multa::query()->orderBy('nombre');

        $termino = isset($filtros['q']) ? trim((string) $filtros['q']) : '';
        if ($termino !== '') {
            $like = '%'.$termino.'%';
            $consulta->where(function (Builder $q) use ($like): void {
                $q->where('nombre', 'like', $like)
                    ->orWhere('descripcion', 'like', $like);
            });
        }

        $activa = $filtros['activa'] ?? null;
        if ($activa === '1' || $activa === '0' || $activa === 1 || $activa === 0) {
            $consulta->where('activa', (bool) (int) $activa);
        }

        return $consulta->paginate($porPagina)->withQueryString();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Multa>
     */
    public function listarActivasOrdenadas()
    {
        return Multa::query()->where('activa', true)->orderBy('nombre')->get();
    }

    /**
     * @param  array{nombre: string, descripcion: ?string, monto: float|int|string, activa: bool}  $datos
     */
    public function crear(array $datos): Multa
    {
        return Multa::query()->create([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'monto' => $datos['monto'],
            'activa' => (bool) $datos['activa'],
        ]);
    }

    /**
     * @param  array{nombre: string, descripcion: ?string, monto: float|int|string, activa: bool}  $datos
     */
    public function actualizar(Multa $multa, array $datos): Multa
    {
        $multa->fill([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'monto' => $datos['monto'],
            'activa' => (bool) $datos['activa'],
        ]);
        $multa->save();

        return $multa->fresh();
    }
}
