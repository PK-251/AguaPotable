@extends('components.layouts.app')

@section('title', 'Detalle padrón')

@section('content')
    @php
        $nombreCompleto = trim($residente->nombre.' '.$residente->apellido);
        $iniciales = mb_strtoupper(mb_substr(trim($residente->nombre), 0, 1).mb_substr(trim($residente->apellido), 0, 1));
    @endphp

    <x-ui.page-header
        :title="$nombreCompleto"
        :subtitle="'Código '.$residente->codigo">
        <x-slot name="actions">
            <a href="{{ route('agua.cobros.index', ['padron' => $residente->id]) }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="point_of_sale" size="sm" /> Ir a cobros
            </a>
            <a href="{{ route('agua.padron.edit', $residente) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="edit" size="sm" /> Editar
            </a>
            <a href="{{ route('agua.padron.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="arrow_back" size="sm" /> Volver al listado
            </a>
        </x-slot>
    </x-ui.page-header>

    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <x-ui.card title="Identificación" icon="badge">
                <div class="d-inline-flex align-items-center gap-2 mb-3">
                    <x-ui.avatar :initials="$iniciales" tone="primary" />
                    <div>
                        <p class="fw-semibold mb-0">{{ $nombreCompleto }}</p>
                        <p class="text-body-secondary small mb-0">{{ $residente->codigo }}</p>
                    </div>
                </div>
                <p class="small text-body-secondary mb-1">Estado</p>
                <x-ui.status-badge
                    :label="$residente->estado === 'activo' ? 'Activo' : 'Cortado'"
                    :tone="$residente->estado === 'activo' ? 'success' : 'danger'" />
                <hr>
                <p class="small text-body-secondary mb-1">Dirección</p>
                <p class="mb-0">{{ $residente->direccion }}</p>
            </x-ui.card>
        </div>
        <div class="col-lg-4">
            <x-ui.card title="Tarifa asignada" icon="sell">
                @if ($residente->tarifa)
                    <p class="fw-semibold mb-1">{{ $residente->tarifa->nombre }}</p>
                    <p class="mb-2"><x-ui.money :amount="$residente->tarifa->monto" /></p>
                    @if ($residente->tarifa->descripcion)
                        <p class="text-body-secondary small mb-0">{{ $residente->tarifa->descripcion }}</p>
                    @endif
                @else
                    <p class="text-body-secondary mb-0">Sin tarifa vinculada.</p>
                @endif
            </x-ui.card>
        </div>
        <div class="col-lg-4">
            <x-ui.card title="Deuda registrada" icon="account_balance_wallet">
                <p class="h4 mb-0"><x-ui.money :amount="$deudaPendiente" /></p>
                <p class="text-body-secondary small mb-0">Suma de cobros en estado pendiente.</p>
            </x-ui.card>
        </div>
    </div>

    <x-ui.card title="Últimos movimientos de cobro" icon="receipt_long" class="mt-3">
        @if ($residente->pagos->isEmpty())
            <x-ui.empty-state icon="receipt_long" title="Sin cobros registrados" message="Los pagos o cobros pendientes aparecerán cuando se operen desde el módulo de cobros." />
        @else
            <div class="table-responsive">
                <table class="table agua-table mb-0">
                    <thead>
                        <tr>
                            <th>Periodo</th>
                            <th class="text-end">Monto</th>
                            <th>Estado</th>
                            <th>Registrado por</th>
                            <th class="text-end">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($residente->pagos as $pago)
                            <tr>
                                <td>{{ $pago->periodo }}</td>
                                <td class="text-end text-nowrap"><x-ui.money :amount="$pago->monto_total" /></td>
                                <td>
                                    <x-ui.status-badge
                                        :label="$pago->estado === 'pagado' ? 'Pagado' : 'Pendiente'"
                                        :tone="$pago->estado === 'pagado' ? 'success' : 'warning'" />
                                </td>
                                <td class="text-body-secondary">{{ $pago->user?->name ?? '—' }}</td>
                                <td class="text-end text-body-secondary">{{ $pago->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-ui.card>
@endsection
