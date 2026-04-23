@extends('components.layouts.portal')

@section('title', 'Estado de cuenta')

@section('content')
    @php
        $historial = $historial ?? [
            ['fecha' => '12 Abril, 2024',  'mes' => 'Abril',   'monto' => '20.00', 'chip' => null],
            ['fecha' => '15 Marzo, 2024',  'mes' => 'Marzo',   'monto' => '20.00', 'chip' => null],
            ['fecha' => '10 Febrero, 2024','mes' => 'Febrero', 'monto' => '20.00', 'chip' => null],
            ['fecha' => '08 Enero, 2024',  'mes' => 'Enero',   'monto' => '22.50', 'chip' => ['Incluye mora', 'muted']],
        ];
    @endphp

    <header class="mb-4">
        <h1 class="h2 fw-bold mb-1">Mi estado de cuenta</h1>
        <p class="text-body-secondary mb-0">Consulta el saldo actual de tu servicio de agua potable y tu historial de pagos.</p>
    </header>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-5">
            <div class="agua-debt-card">
                <div class="d-flex justify-content-between align-items-start">
                    <p class="agua-debt-card__label">Deuda total</p>
                    <x-ui.icon name="account_balance_wallet" class="text-white" />
                </div>
                <p class="agua-debt-card__amount-label">Monto a pagar</p>
                <p class="agua-debt-card__amount">S/ 25.00</p>
                <span class="agua-debt-card__status">
                    <x-ui.icon name="priority_high" size="sm" /> Pendiente
                </span>
            </div>
        </div>

        <div class="col-12 col-md-7">
            <x-ui.card>
                <x-slot name="header">
                    <div class="d-flex align-items-center gap-2">
                        <x-ui.icon name="receipt_long" class="text-primary" />
                        <span class="fw-semibold">Detalle del saldo</span>
                    </div>
                </x-slot>

                <ul class="list-unstyled d-flex flex-column gap-3 mb-3">
                    <li class="d-flex justify-content-between align-items-center">
                        <div class="d-inline-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                  style="width:32px;height:32px;background-color:var(--agua-surface-alt);color:var(--agua-text);">
                                <x-ui.icon name="water_drop" size="sm" />
                            </span>
                            <div>
                                <div class="fw-semibold">Cuota mensual</div>
                                <div class="text-body-secondary small">Mes Mayo</div>
                            </div>
                        </div>
                        <x-ui.money amount="20.00" />
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <div class="d-inline-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                  style="width:32px;height:32px;background-color:var(--agua-danger-soft);color:var(--agua-danger);">
                                <x-ui.icon name="timer" size="sm" />
                            </span>
                            <div>
                                <div class="fw-semibold">Mora por retraso</div>
                                <div class="text-body-secondary small">Atraso de 15 días</div>
                            </div>
                        </div>
                        <x-ui.money amount="5.00" tone="danger" />
                    </li>
                </ul>

                <div class="d-flex justify-content-end gap-2 flex-wrap">
                    <a href="#" class="btn btn-outline-secondary btn-sm">Ver detalle completo</a>
                    <a href="#" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                        <x-ui.icon name="payments" size="sm" /> Instrucciones de pago
                    </a>
                </div>
            </x-ui.card>
        </div>
    </div>

    <h2 class="agua-section-title d-inline-flex align-items-center gap-2">
        <x-ui.icon name="history" class="text-primary" /> Historial de pagos
    </h2>

    <x-ui.data-table>
        <x-slot name="toolbar">
            <a href="#" class="ms-auto text-primary small fw-semibold d-inline-flex align-items-center gap-1">
                <x-ui.icon name="tune" size="sm" /> Filtrar
            </a>
        </x-slot>

        <thead>
            <tr>
                <th>Fecha de pago</th>
                <th>Mes pagado</th>
                <th class="text-end">Monto</th>
                <th class="text-end" style="width:130px;">Comprobante</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historial as $h)
                <tr>
                    <td class="text-body-secondary">{{ $h['fecha'] }}</td>
                    <td>
                        {{ $h['mes'] }}
                        @if ($h['chip'])
                            <x-ui.status-badge :label="$h['chip'][0]" :tone="$h['chip'][1]" />
                        @endif
                    </td>
                    <td class="text-end"><x-ui.money :amount="$h['monto']" /></td>
                    <td class="text-end">
                        <a href="#" class="agua-icon-btn" aria-label="Descargar comprobante">
                            <x-ui.icon name="download" size="sm" />
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">Mostrando 1 a 4 de 24 registros</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                    <li class="page-item"><a class="page-link" href="#">&rsaquo;</a></li>
                </ul>
            </nav>
        </x-slot>
    </x-ui.data-table>
@endsection
