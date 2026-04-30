@extends('components.layouts.app')

@section('title', 'Tarifas')

@section('content')
    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.page-header
        title="Gestión de tarifas"
        subtitle="Consulte montos y vigencias. La tarifa asignada en el padrón es la referencia para cobros.">
        <x-slot name="actions">
            @if (auth()->user()->esAdmin())
                <a href="{{ route('agua.tarifas.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2" data-cy="tarifa-nueva">
                    <x-ui.icon name="add" size="sm" /> Nueva tarifa
                </a>
            @endif
        </x-slot>
    </x-ui.page-header>

    <form method="get" action="{{ route('agua.tarifas.index') }}" class="mb-3">
        <x-ui.filter-bar>
            <x-ui.search-input label="Buscar" name="q" placeholder="Nombre o descripción" :value="$filtros['q'] ?? ''" />
            <div class="d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('agua.tarifas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </x-ui.filter-bar>
    </form>

    <x-ui.data-table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="text-end">Monto (S/)</th>
                <th>Referencia</th>
                <th>Vigente desde</th>
                <th class="text-end" style="width:120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tarifas as $t)
                @php
                    $enVigor = $t->vigente_desde && $t->vigente_desde->lte(now()->startOfDay());
                @endphp
                <tr>
                    <td class="fw-semibold">{{ $t->nombre }}</td>
                    <td class="text-body-secondary">{{ $t->descripcion ?: '—' }}</td>
                    <td class="text-end"><x-ui.money :amount="$t->monto" currency="" /></td>
                    <td>
                        <x-ui.status-badge
                            :label="$enVigor ? 'En vigor' : 'Futura'"
                            :tone="$enVigor ? 'success' : 'warning'" />
                    </td>
                    <td class="text-body-secondary">{{ $t->vigente_desde?->format('d/m/Y') }}</td>
                    <td class="text-end">
                        <div class="agua-actions">
                            <a href="{{ route('agua.tarifas.show', $t) }}" class="agua-icon-btn" aria-label="Ver">
                                <x-ui.icon name="visibility" size="sm" />
                            </a>
                            @if (auth()->user()->esAdmin())
                                <a href="{{ route('agua.tarifas.edit', $t) }}" class="agua-icon-btn" aria-label="Editar">
                                    <x-ui.icon name="edit" size="sm" />
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <x-ui.empty-state
                            icon="sell"
                            title="Sin tarifas registradas"
                            message="Defina al menos una tarifa para asignarla a los usuarios del padrón." />
                    </td>
                </tr>
            @endforelse
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">
                @if ($tarifas->total() > 0)
                    Mostrando {{ $tarifas->firstItem() }}–{{ $tarifas->lastItem() }} de {{ $tarifas->total() }} registros
                @else
                    Sin registros
                @endif
            </div>
            {{ $tarifas->links() }}
        </x-slot>
    </x-ui.data-table>
@endsection
