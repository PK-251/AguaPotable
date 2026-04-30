@extends('components.layouts.app')

@section('title', 'Catálogo de multas')

@section('content')
    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.page-header
        title="Catálogo de multas"
        subtitle="Defina penalidades de monto fijo. Las multas activas pueden aplicarse a usuarios del padrón.">
        <x-slot name="actions">
            @if (auth()->user()->esAdmin())
                <a href="{{ route('agua.multas.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2" data-cy="multa-nueva">
                    <x-ui.icon name="add" size="sm" /> Nueva multa
                </a>
            @endif
        </x-slot>
    </x-ui.page-header>

    <form method="get" action="{{ route('agua.multas.index') }}" class="mb-3">
        <x-ui.filter-bar>
            <x-ui.search-input label="Buscar" name="q" placeholder="Nombre o descripción" :value="$filtros['q'] ?? ''" />
            <div>
                <label class="form-label">Activa</label>
                <select class="form-select" name="activa">
                    <option value="" @selected(($filtros['activa'] ?? '') === '')>Todas</option>
                    <option value="1" @selected((string) ($filtros['activa'] ?? '') === '1')>Solo activas</option>
                    <option value="0" @selected((string) ($filtros['activa'] ?? '') === '0')>Solo inactivas</option>
                </select>
            </div>
            <div class="d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('agua.multas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </x-ui.filter-bar>
    </form>

    <x-ui.data-table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Descripción</th>
                <th class="text-end">Monto (S/)</th>
                <th>Estado</th>
                <th class="text-end" style="width:120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($multas as $m)
                <tr>
                    <td class="fw-semibold">{{ $m->nombre }}</td>
                    <td class="text-body-secondary">{{ Str::limit($m->descripcion ?? '', 80) ?: '—' }}</td>
                    <td class="text-end"><x-ui.money :amount="$m->monto" currency="" /></td>
                    <td>
                        <x-ui.status-badge
                            :label="$m->activa ? 'Activa' : 'Inactiva'"
                            :tone="$m->activa ? 'success' : 'muted'" caps dotless />
                    </td>
                    <td class="text-end">
                        <div class="agua-actions">
                            <a href="{{ route('agua.multas.show', $m) }}" class="agua-icon-btn" aria-label="Ver">
                                <x-ui.icon name="visibility" size="sm" />
                            </a>
                            @if (auth()->user()->esAdmin())
                                <a href="{{ route('agua.multas.edit', $m) }}" class="agua-icon-btn" aria-label="Editar">
                                    <x-ui.icon name="edit" size="sm" />
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <x-ui.empty-state
                            icon="warning"
                            title="Sin multas en catálogo"
                            message="Registre conceptos para poder aplicarlos desde el módulo de multas por usuario." />
                    </td>
                </tr>
            @endforelse
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">
                @if ($multas->total() > 0)
                    Mostrando {{ $multas->firstItem() }}–{{ $multas->lastItem() }} de {{ $multas->total() }} registros
                @else
                    Sin registros
                @endif
            </div>
            {{ $multas->links() }}
        </x-slot>
    </x-ui.data-table>
@endsection
