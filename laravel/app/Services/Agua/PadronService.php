<?php

namespace App\Services\Agua;

use App\Models\PadronUsuario;
use App\Models\Tarifa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PadronService
{
    /**
     * @param  array{
     *     q?: string|null,
     *     estado?: string|null,
     *     tarifa_id?: int|string|null
     * }  $filtros
     */
    public function paginar(array $filtros = [], int $porPagina = 15): LengthAwarePaginator
    {
        $consulta = PadronUsuario::query()
            ->with(['tarifa:id,nombre,monto'])
            ->withSum([
                'pagos as deuda_pendiente_sum' => static fn (Builder $q): Builder => $q->where('estado', 'pendiente'),
            ], 'monto_total')
            ->orderBy('codigo');

        $this->aplicarBusqueda($consulta, $filtros['q'] ?? null);
        $this->aplicarFiltroEstado($consulta, $filtros['estado'] ?? null);
        $this->aplicarFiltroTarifa($consulta, $filtros['tarifa_id'] ?? null);

        return $consulta->paginate($porPagina)->withQueryString();
    }

    /**
     * @param  array{
     *     codigo: string,
     *     nombre: string,
     *     apellido: string,
     *     direccion: string,
     *     estado: string,
     *     tarifa_id: int
     * }  $datos
     */
    public function crear(array $datos): PadronUsuario
    {
        return PadronUsuario::query()->create([
            'codigo' => $datos['codigo'],
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'direccion' => $datos['direccion'],
            'estado' => $datos['estado'],
            'tarifa_id' => $datos['tarifa_id'],
        ]);
    }

    /**
     * @param  array{
     *     codigo: string,
     *     nombre: string,
     *     apellido: string,
     *     direccion: string,
     *     estado: string,
     *     tarifa_id: int
     * }  $datos
     */
    public function actualizar(PadronUsuario $usuario, array $datos): PadronUsuario
    {
        $usuario->fill([
            'codigo' => $datos['codigo'],
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'direccion' => $datos['direccion'],
            'estado' => $datos['estado'],
            'tarifa_id' => $datos['tarifa_id'],
        ]);
        $usuario->save();

        return $usuario->fresh(['tarifa']);
    }

    private function aplicarBusqueda(Builder $consulta, ?string $termino): void
    {
        $termino = $termino !== null ? trim($termino) : '';
        if ($termino === '') {
            return;
        }

        $like = '%'.$termino.'%';

        $consulta->where(function (Builder $q) use ($like): void {
            $q->where('codigo', 'like', $like)
                ->orWhere('nombre', 'like', $like)
                ->orWhere('apellido', 'like', $like)
                ->orWhere('direccion', 'like', $like);
        });
    }

    private function aplicarFiltroEstado(Builder $consulta, ?string $estado): void
    {
        if ($estado === null || $estado === '' || $estado === 'todos') {
            return;
        }

        if (! in_array($estado, ['activo', 'cortado'], true)) {
            return;
        }

        $consulta->where('estado', $estado);
    }

    private function aplicarFiltroTarifa(Builder $consulta, int|string|null $tarifaId): void
    {
        if ($tarifaId === null || $tarifaId === '' || $tarifaId === '0') {
            return;
        }

        $consulta->where('tarifa_id', (int) $tarifaId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Tarifa>
     */
    public function listarTarifasOrdenadas()
    {
        return Tarifa::query()->orderBy('nombre')->get(['id', 'nombre', 'monto']);
    }
}
