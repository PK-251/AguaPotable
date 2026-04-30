@extends('components.layouts.app')

@section('title', 'Multas por usuario')

@section('content')
    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.page-header
        title="Multas aplicadas al padrón"
        subtitle="Consulte y aplique multas del catálogo. Integrado con el flujo de cobros.">
        <x-slot name="actions">
            <a href="{{ route('agua.multas-usuario.create', ['padron_usuario_id' => $padronPreseleccionado?->id]) }}" class="btn btn-primary d-inline-flex align-items-center gap-2" data-cy="multa-aplicar">
                <x-ui.icon name="add" size="sm" /> Aplicar multa
            </a>
        </x-slot>
    </x-ui.page-header>

    <form method="get" action="{{ route('agua.multas-usuario.index') }}" class="mb-3">
        <x-ui.filter-bar>
            <x-ui.search-input label="Buscar" name="q" placeholder="Código, nombre o dirección" :value="$filtros['q'] ?? ''" />
            <div>
                <label class="form-label">Estado de pago</label>
                <select class="form-select" name="pagada">
                    <option value="" @selected(($filtros['pagada'] ?? '') === '')>Todos</option>
                    <option value="0" @selected((string) ($filtros['pagada'] ?? '') === '0')>Pendiente</option>
                    <option value="1" @selected((string) ($filtros['pagada'] ?? '') === '1')>Pagada</option>
                </select>
            </div>
            <div>
                <label class="form-label">Concepto</label>
                <select class="form-select" name="multa_id">
                    <option value="">Todos</option>
                    @foreach ($multasParaFiltro as $multa)
                        <option value="{{ $multa->id }}" @selected((string) ($filtros['multa_id'] ?? '') === (string) $multa->id)>
                            {{ $multa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex align-items-end gap-2 flex-wrap">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('agua.multas-usuario.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </x-ui.filter-bar>
    </form>

    @if ($padronPreseleccionado)
        <x-ui.alert variant="info" icon="info" class="mb-3">
            Filtrando multas del usuario <strong>{{ $padronPreseleccionado->codigo }}</strong> —
            <a href="{{ route('agua.multas-usuario.index') }}">Ver todas</a>
        </x-ui.alert>
    @endif

    <x-ui.data-table>
        <thead>
            <tr>
                <th>Residente</th>
                <th>Concepto</th>
                <th>Mes</th>
                <th class="text-end">Monto</th>
                <th>Estado</th>
                <th class="text-end" style="width:100px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($asignaciones as $asignacion)
                @php
                    $padron = $asignacion->padronUsuario;
                    $nombre = $padron ? trim($padron->nombre.' '.$padron->apellido) : '—';
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $nombre }}</div>
                        <div class="text-body-secondary small">{{ $padron?->codigo }}</div>
                    </td>
                    <td>{{ $asignacion->multa?->nombre ?? '—' }}</td>
                    <td>{{ $asignacion->mes }}</td>
                    <td class="text-end"><x-ui.money :amount="$asignacion->monto" currency="" /></td>
                    <td>
                        <x-ui.status-badge
                            :label="$asignacion->pagada ? 'Pagada' : 'Pendiente'"
                            :tone="$asignacion->pagada ? 'success' : 'warning'" />
                    </td>
                    <td class="text-end">
                        @if ($padron)
                            <a href="{{ route('agua.cobros.index', ['padron' => $padron->id]) }}" class="btn btn-sm btn-outline-primary">Cobrar</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <x-ui.empty-state
                            icon="warning"
                            title="Sin multas aplicadas"
                            message="Aplique una multa desde el catálogo o revise los filtros." />
                    </td>
                </tr>
            @endforelse
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">
                @if ($asignaciones->total() > 0)
                    Mostrando {{ $asignaciones->firstItem() }}–{{ $asignaciones->lastItem() }} de {{ $asignaciones->total() }}
                @else
                    Sin registros
                @endif
            </div>
            {{ $asignaciones->links() }}
        </x-slot>
    </x-ui.data-table>
@endsection
