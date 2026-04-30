@extends('components.layouts.app')

@section('title', 'Padrón de usuarios')

@section('content')
    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.page-header
        title="Padrón de usuarios"
        subtitle="Gestión y registro de usuarios del servicio de agua potable.">
        <x-slot name="actions">
            <a href="{{ route('agua.import-export.padron') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="download" size="sm" /> Importar / exportar
            </a>
            <a href="{{ route('agua.padron.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2" data-cy="padron-nuevo">
                <x-ui.icon name="add" size="sm" /> Nuevo usuario
            </a>
        </x-slot>
    </x-ui.page-header>

    <form method="get" action="{{ route('agua.padron.index') }}" class="mb-3">
        <x-ui.filter-bar>
            <x-ui.search-input label="Buscar" placeholder="Código, nombre o dirección" name="q" :value="$filtros['q'] ?? ''" />

            <div>
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="todos" @selected(($filtros['estado'] ?? 'todos') === 'todos')>Todos</option>
                    <option value="activo" @selected(($filtros['estado'] ?? '') === 'activo')>Activo</option>
                    <option value="cortado" @selected(($filtros['estado'] ?? '') === 'cortado')>Cortado</option>
                </select>
            </div>

            <div>
                <label class="form-label">Tarifa</label>
                <select class="form-select" name="tarifa_id">
                    <option value="">Todas</option>
                    @foreach ($tarifas as $tarifa)
                        <option value="{{ $tarifa->id }}" @selected((string) ($filtros['tarifa_id'] ?? '') === (string) $tarifa->id)>
                            {{ $tarifa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('agua.padron.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </x-ui.filter-bar>
    </form>

    <x-ui.data-table>
        <thead>
            <tr>
                <th style="width:110px;">Código</th>
                <th>Nombre completo</th>
                <th>Dirección</th>
                <th>Tarifa</th>
                <th>Estado</th>
                <th class="text-end">Deuda pendiente</th>
                <th class="text-end" style="width:120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($residentes as $r)
                @php
                    $nombreCompleto = trim($r->nombre.' '.$r->apellido);
                    $iniciales = mb_strtoupper(mb_substr(trim($r->nombre), 0, 1).mb_substr(trim($r->apellido), 0, 1));
                    $deuda = $r->deuda_pendiente_sum ?? 0;
                @endphp
                <tr>
                    <td class="text-body-secondary">{{ $r->codigo }}</td>
                    <td>
                        <div class="d-inline-flex align-items-center gap-2">
                            <x-ui.avatar :initials="$iniciales" tone="muted" />
                            <span>{{ $nombreCompleto }}</span>
                        </div>
                    </td>
                    <td class="text-body-secondary">{{ $r->direccion }}</td>
                    <td>{{ $r->tarifa?->nombre ?? '—' }}</td>
                    <td>
                        <x-ui.status-badge
                            :label="$r->estado === 'activo' ? 'Activo' : 'Cortado'"
                            :tone="$r->estado === 'activo' ? 'success' : 'danger'" />
                    </td>
                    <td class="text-end">
                        <x-ui.money :amount="$deuda" :tone="(float) $deuda > 0 ? 'warning' : null" />
                    </td>
                    <td class="text-end">
                        <div class="agua-actions">
                            <a href="{{ route('agua.padron.show', $r) }}" class="agua-icon-btn" aria-label="Ver">
                                <x-ui.icon name="visibility" size="sm" />
                            </a>
                            <a href="{{ route('agua.padron.edit', $r) }}" class="agua-icon-btn" aria-label="Editar">
                                <x-ui.icon name="edit" size="sm" />
                            </a>
                            <a href="{{ route('agua.cobros.index', ['padron' => $r->id]) }}" class="agua-icon-btn" aria-label="Cobrar">
                                <x-ui.icon name="point_of_sale" size="sm" />
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <x-ui.empty-state icon="group_off" title="Sin residentes" message="No hay coincidencias o el padrón aún está vacío. Cree el primer registro para comenzar." />
                    </td>
                </tr>
            @endforelse
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">
                @if ($residentes->total() > 0)
                    Mostrando {{ $residentes->firstItem() }}–{{ $residentes->lastItem() }} de {{ $residentes->total() }} residentes
                @else
                    Sin registros para mostrar
                @endif
            </div>
            {{ $residentes->links() }}
        </x-slot>
    </x-ui.data-table>
@endsection
