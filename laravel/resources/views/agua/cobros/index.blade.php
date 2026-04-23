@extends('components.layouts.app')

@section('title', 'Cobros')

@section('content')
    @php
        $transacciones = $transacciones ?? [
            ['nombre' => 'María López', 'cod' => '0112', 'recibo' => '…', 'monto' => '20.00', 'hora' => '10:45 AM'],
            ['nombre' => 'Carlos Ruiz', 'cod' => '0089', 'recibo' => '…', 'monto' => '45.00', 'hora' => '09:30 AM'],
            ['nombre' => 'Ana Torres',  'cod' => '0234', 'recibo' => '…', 'monto' => '20.00', 'hora' => '08:15 AM'],
        ];

        $detalle = $detalle ?? [
            ['concepto' => 'Mensualidad Mayo 2024', 'chip' => null,                 'monto' => '20.00'],
            ['concepto' => 'Mora / Multa',          'chip' => ['ATRASO','danger'],  'monto' => '5.00'],
            ['concepto' => 'Deuda anterior',        'chip' => null,                 'monto' => '0.00'],
        ];
    @endphp

    <x-ui.page-header
        title="Gestión de Cobros"
        subtitle="Busque un usuario para registrar su pago mensual o regularizar deudas." />

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <x-ui.card :padded="false">
                <div class="p-3 border-bottom">
                    <x-ui.search-input placeholder="Buscar residente, código..." name="residente" />
                </div>

                <div class="p-3 border-bottom d-flex align-items-center gap-3 flex-wrap">
                    <x-ui.avatar initials="JP" size="48" tone="primary" />
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-semibold">Juan Pérez García</span>
                            <x-ui.status-badge label="Activo" tone="success" />
                        </div>
                        <div class="text-body-secondary small d-flex align-items-center gap-3 flex-wrap mt-1">
                            <span class="d-inline-flex align-items-center gap-1"><x-ui.icon name="tag" size="sm" /> Cód: 0045</span>
                            <span class="d-inline-flex align-items-center gap-1"><x-ui.icon name="location_on" size="sm" /> Calle Arequipa 123</span>
                        </div>
                    </div>
                    <a href="#" class="text-primary small fw-semibold">Cambiar usuario</a>
                </div>

                <div class="p-3">
                    <div class="d-flex align-items-center gap-2 fw-semibold mb-3">
                        <x-ui.icon name="receipt_long" class="text-primary" />
                        <span>Detalle de Deuda</span>
                    </div>

                    <ul class="list-unstyled d-flex flex-column gap-2 mb-3">
                        @foreach ($detalle as $d)
                            <li class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                <span class="d-inline-flex align-items-center gap-2">
                                    {{ $d['concepto'] }}
                                    @if ($d['chip'])
                                        <x-ui.status-badge :label="$d['chip'][0]" :tone="$d['chip'][1]" caps />
                                    @endif
                                </span>
                                <x-ui.money :amount="$d['monto']" />
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded"
                         style="background-color: var(--agua-primary); color: var(--agua-text-on-primary);">
                        <span class="fw-semibold">Total a pagar</span>
                        <span class="fs-3 fw-bold">S/ 25.00</span>
                    </div>

                    <div class="d-flex gap-2 mt-3 flex-wrap">
                        <button type="button" class="btn btn-primary flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2">
                            <x-ui.icon name="point_of_sale" /> Registrar Pago
                        </button>
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <x-ui.icon name="history" /> Ver Historial
                        </button>
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <x-ui.icon name="description" /> Comprobante
                        </button>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="col-12 col-xl-4">
            <x-ui.card>
                <x-slot name="header">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <h2 class="h6 mb-0 fw-semibold">Últimas transacciones</h2>
                        <x-ui.status-badge label="Hoy" tone="info" dotless />
                    </div>
                </x-slot>

                <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                    @foreach ($transacciones as $tx)
                        <li class="d-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                  style="width:32px;height:32px;background-color:var(--agua-primary-soft);color:var(--agua-primary);">
                                <x-ui.icon name="check_circle" size="sm" filled />
                            </span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">{{ $tx['nombre'] }}</div>
                                <div class="text-body-secondary small">Cód: {{ $tx['cod'] }} · Recibo #{{ $tx['recibo'] }}</div>
                            </div>
                            <div class="text-end">
                                <div class="text-primary fw-bold small"><x-ui.money :amount="$tx['monto']" /></div>
                                <div class="text-body-secondary small">{{ $tx['hora'] }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="text-center mt-3">
                    <a href="#" class="small text-primary">Ver todas las transacciones</a>
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
