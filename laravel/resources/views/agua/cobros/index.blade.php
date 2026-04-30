@extends('components.layouts.app')

@section('title', 'Cobros')

@section('content')
    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.form-errors :errors="$errors" />

    <x-ui.page-header
        title="Gestión de cobros"
        subtitle="Busque un usuario del padrón, revise el resumen y registre pagos o cobros pendientes." />

    <div class="row g-3">
        <div class="col-12 col-xl-8 d-flex flex-column gap-3">
            <x-ui.card :padded="false">
                <form method="get" action="{{ route('agua.cobros.index') }}" class="p-3 border-bottom">
                    <input type="hidden" name="padron" value="">
                    <div class="d-flex flex-column flex-md-row gap-2 align-items-stretch align-items-md-end">
                        <div class="flex-grow-1">
                            <label class="form-label small text-body-secondary mb-1">Buscar usuario</label>
                            <x-ui.search-input name="q" placeholder="Código, nombre o dirección" :value="$busqueda" />
                        </div>
                        <button type="submit" class="btn btn-primary" data-cy="cobros-buscar">Buscar</button>
                    </div>
                </form>

                @if ($padron === null && $residentesCoincidencias->isNotEmpty())
                    <div class="p-3 border-bottom">
                        <p class="fw-semibold mb-2">Resultados</p>
                        <div class="list-group">
                            @foreach ($residentesCoincidencias as $candidato)
                                @php
                                    $nombre = trim($candidato->nombre.' '.$candidato->apellido);
                                @endphp
                                <a
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                    href="{{ route('agua.cobros.index', ['padron' => $candidato->id, 'periodo' => $periodo]) }}">
                                    <div>
                                        <span class="fw-semibold d-block">{{ $nombre ?: $candidato->codigo }}</span>
                                        <span class="text-body-secondary small">Cód. {{ $candidato->codigo }} · {{ $candidato->tarifa?->nombre ?? 'Sin tarifa' }}</span>
                                    </div>
                                    <x-ui.icon name="chevron_right" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($padron === null && $busqueda !== '' && $residentesCoincidencias->isEmpty())
                    <div class="p-3">
                        <x-ui.empty-state
                            icon="search_off"
                            title="Sin coincidencias"
                            :message="'No se encontraron usuarios para «'.$busqueda.'».'" />
                    </div>
                @endif

                @if ($padronSolicitadoNoEncontrado ?? false)
                    <div class="p-3">
                        <x-ui.alert variant="warning" icon="warning">
                            El usuario solicitado no existe o ya no está disponible en el padrón.
                        </x-ui.alert>
                    </div>
                @endif

                @if ($resumen)
                    @php
                        $iniciales = mb_strtoupper(mb_substr(trim($padron->nombre), 0, 1).mb_substr(trim($padron->apellido), 0, 1));
                    @endphp

                    <div class="p-3 border-bottom d-flex align-items-center gap-3 flex-wrap">
                        <x-ui.avatar :initials="$iniciales" size="48" tone="primary" />
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-semibold">{{ $resumen->nombreCompleto }}</span>
                                <x-ui.status-badge
                                    :label="$resumen->estado === 'activo' ? 'Activo' : 'Cortado'"
                                    :tone="$resumen->estado === 'activo' ? 'success' : 'danger'" />
                            </div>
                            <div class="text-body-secondary small d-flex align-items-center gap-3 flex-wrap mt-1">
                                <span class="d-inline-flex align-items-center gap-1">
                                    <x-ui.icon name="tag" size="sm" /> Cód: {{ $resumen->codigo }}
                                </span>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <x-ui.icon name="location_on" size="sm" /> {{ $resumen->direccion }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('agua.cobros.index', ['periodo' => $periodo]) }}" class="text-primary small fw-semibold">Cambiar usuario</a>
                    </div>

                    <div class="p-3 border-bottom">
                        <div class="d-flex align-items-center gap-2 fw-semibold mb-3">
                            <x-ui.icon name="receipt_long" class="text-primary" />
                            <span>Detalle de deuda sugerida</span>
                        </div>

                        @if ($resumen->tarifaNombre)
                            <p class="small text-body-secondary mb-2">
                                Tarifa vigente:
                                <span class="fw-semibold text-body">{{ $resumen->tarifaNombre }}</span>
                                @if ($resumen->tarifaMonto)
                                    (<x-ui.money :amount="$resumen->tarifaMonto" />)
                                @endif
                            </p>
                        @endif

                        <ul class="list-unstyled d-flex flex-column gap-2 mb-3">
                            @forelse ($resumen->lineas as $detalle)
                                <li class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="d-inline-flex align-items-center gap-2 flex-wrap">
                                        {{ $detalle['concepto'] }}
                                        @if (! empty($detalle['badge']))
                                            <x-ui.status-badge :label="$detalle['badge']" :tone="$detalle['tone'] ?? 'muted'" caps />
                                        @endif
                                    </span>
                                    <x-ui.money :amount="$detalle['monto']" />
                                </li>
                            @empty
                                <li class="text-body-secondary">Sin conceptos pendientes para este periodo.</li>
                            @endforelse
                        </ul>

                        <div class="d-flex justify-content-between align-items-center p-3 rounded"
                             style="background-color: var(--agua-primary); color: var(--agua-text-on-primary);">
                            <span class="fw-semibold">Total referencial</span>
                            <span class="fs-3 fw-bold">S/ {{ number_format((float) $resumen->total, 2) }}</span>
                        </div>

                        @if ($resumen->existePagadoParaPeriodo)
                            <x-ui.alert variant="success" icon="task_alt" class="mt-3 mb-0">
                                Este periodo ya tiene un cobro registrado como pagado.
                            </x-ui.alert>
                        @else
                            <form method="post" action="{{ route('agua.cobros.store') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="padron_usuario_id" value="{{ $resumen->padronId }}">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-body-secondary mb-1">Periodo</label>
                                        <input
                                            type="month"
                                            name="periodo"
                                            class="form-control @error('periodo') is-invalid @enderror"
                                            value="{{ old('periodo', $resumen->periodo) }}"
                                            required
                                            data-cy="cobros-periodo">
                                        @error('periodo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-body-secondary mb-1">Registrar como</label>
                                        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required data-cy="cobros-estado">
                                            <option value="pagado" @selected(old('estado', 'pagado') === 'pagado')>Pagado (liquida pendientes y multas)</option>
                                            <option value="pendiente" @selected(old('estado') === 'pendiente')>Pendiente (solo cuota del periodo)</option>
                                        </select>
                                        @error('estado')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2" data-cy="cobros-registrar">
                                        <x-ui.icon name="point_of_sale" /> Registrar cobro
                                    </button>
                                    <a href="{{ route('agua.padron.show', $padron) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                                        <x-ui.icon name="badge" size="sm" /> Ficha de padrón
                                    </a>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif

                @if ($padron === null && $busqueda === '' && ! ($padronSolicitadoNoEncontrado ?? false))
                    <div class="p-3">
                        <x-ui.empty-state
                            icon="manage_search"
                            title="Seleccione un usuario"
                            message="Use el buscador para ubicar al vecino y registrar el cobro desde esta pantalla." />
                    </div>
                @endif
            </x-ui.card>
        </div>

        <div class="col-12 col-xl-4">
            <x-ui.card>
                <x-slot name="header">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <h2 class="h6 mb-0 fw-semibold">Últimos cobros</h2>
                        <x-ui.status-badge label="Recientes" tone="info" dotless />
                    </div>
                </x-slot>

                @if ($ultimosPagos->isEmpty())
                    <x-ui.empty-state
                        icon="inbox"
                        title="Sin movimientos"
                        message="Los últimos cobros registrados aparecerán en esta lista." />
                @else
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        @foreach ($ultimosPagos as $pago)
                            @php
                                $titular = $pago->padronUsuario;
                                $nombre = $titular ? trim($titular->nombre.' '.$titular->apellido) : '—';
                            @endphp
                            <li class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                      style="width:32px;height:32px;background-color:var(--agua-primary-soft);color:var(--agua-primary);">
                                    <x-ui.icon name="check_circle" size="sm" filled />
                                </span>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $nombre }}</div>
                                    <div class="text-body-secondary small">
                                        Cód: {{ $titular?->codigo ?? '—' }} · {{ $pago->periodo }} ·
                                        <x-ui.status-badge
                                            :label="$pago->estado === 'pagado' ? 'Pagado' : 'Pendiente'"
                                            :tone="$pago->estado === 'pagado' ? 'success' : 'warning'"
                                            dotless />
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-primary fw-bold small"><x-ui.money :amount="$pago->monto_total" /></div>
                                    @if ($pago->estaPagado())
                                        <a href="{{ route('agua.comprobantes.show', $pago) }}" class="small text-primary d-block">Comprobante</a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>
    </div>
@endsection
