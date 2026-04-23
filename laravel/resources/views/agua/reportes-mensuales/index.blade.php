@extends('components.layouts.app')

@section('title', 'Reportes mensuales')

@section('content')
    @php
        $ingresos = $ingresos ?? [
            ['concepto' => 'Tarifa Residencial',  'sub' => 'Aportes mensuales regulares',  'monto' => '12,500.00', 'peso' => '83% del total', 'icon' => 'water_drop', 'tone' => 'primary'],
            ['concepto' => 'Multas por Retraso',  'sub' => 'Penalidades cobradas',          'monto' => '1,200.00',  'peso' => '8% del total',  'icon' => 'warning',    'tone' => 'warning'],
            ['concepto' => 'Nuevas Conexiones',   'sub' => 'Derechos de instalación',       'monto' => '1,300.00',  'peso' => '9% del total',  'icon' => 'plumbing',   'tone' => 'info'],
        ];

        $deudores = $deudores ?? [
            ['nombre' => 'Carlos Mamani Quispe', 'meses' => '3 meses', 'monto' => '120.00'],
            ['nombre' => 'María Condori Yucra',  'meses' => '2 meses', 'monto' => '80.00'],
            ['nombre' => 'Juan Huaman Tito',     'meses' => '2 meses', 'monto' => '80.00'],
        ];
    @endphp

    <x-ui.card padded>
        <div class="d-flex flex-wrap align-items-center gap-3 justify-content-between">
            <div>
                <h1 class="h3 mb-1 fw-bold">Reportes Operativos</h1>
                <p class="text-body-secondary mb-0">Resumen financiero y operativo mensual de la organización.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select class="form-select" style="width: auto;">
                    <option>Mayo</option><option>Junio</option><option>Julio</option>
                </select>
                <select class="form-select" style="width: auto;">
                    <option>2024</option><option>2023</option>
                </select>
                <a href="#" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <x-ui.icon name="picture_as_pdf" size="sm" /> Descargar Reporte PDF
                </a>
            </div>
        </div>
    </x-ui.card>

    <div class="row g-3 my-1">
        <div class="col-12 col-md-4">
            <x-ui.stat-card
                title="Ingresos totales"
                value="15,000.00"
                prefix="S/"
                tone="primary"
                help="12% vs mes anterior"
                icon="trending_up" />
        </div>
        <div class="col-12 col-md-4">
            <x-ui.stat-card
                title="Egresos totales"
                value="4,000.00"
                prefix="S/"
                tone="danger"
                help="5% vs mes anterior"
                icon="trending_down" />
        </div>
        <div class="col-12 col-md-4">
            <x-ui.stat-card
                title="Saldo caja"
                value="11,000.00"
                prefix="S/"
                tone="filled"
                help="Balance neto del periodo"
                icon="account_balance" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <x-ui.card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="fw-semibold">Desglose de Ingresos</span>
                        <button type="button" class="agua-icon-btn" aria-label="Más opciones">
                            <x-ui.icon name="more_vert" size="sm" />
                        </button>
                    </div>
                </x-slot>

                <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                    @foreach ($ingresos as $row)
                        <li class="d-flex align-items-center gap-3 border-bottom pb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                  style="width:40px;height:40px;background-color:var(--agua-primary-soft);color:var(--agua-primary);">
                                <x-ui.icon :name="$row['icon']" />
                            </span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $row['concepto'] }}</div>
                                <div class="text-body-secondary small">{{ $row['sub'] }}</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold"><x-ui.money :amount="$row['monto']" /></div>
                                <div class="text-body-secondary small">{{ $row['peso'] }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-ui.card>
        </div>

        <div class="col-12 col-lg-6">
            <x-ui.card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="fw-semibold">Resumen de Morosidad</span>
                        <a href="#" class="small text-primary">Ver todos</a>
                    </div>
                </x-slot>

                <div class="d-flex align-items-center justify-content-between p-3 rounded mb-3"
                     style="background-color: var(--agua-danger-soft); color: var(--agua-danger);">
                    <div>
                        <div class="small fw-bold text-uppercase" style="letter-spacing:0.04em;">Deuda total pendiente</div>
                        <div class="fs-4 fw-bold">S/ 2,450.00</div>
                    </div>
                    <span class="d-inline-flex align-items-center justify-content-center rounded"
                          style="width:48px;height:48px;background-color:var(--agua-danger);color:#fff;">
                        <x-ui.icon name="priority_high" />
                    </span>
                </div>

                <p class="fw-semibold mb-2">Principales deudores (Mayo)</p>
                <table class="table agua-table mb-0">
                    <thead>
                        <tr><th>Usuario</th><th>Meses</th><th class="text-end">Monto</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($deudores as $d)
                            <tr>
                                <td>{{ $d['nombre'] }}</td>
                                <td class="text-body-secondary">{{ $d['meses'] }}</td>
                                <td class="text-end text-danger fw-semibold"><x-ui.money :amount="$d['monto']" tone="danger" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-ui.card>
        </div>
    </div>
@endsection
