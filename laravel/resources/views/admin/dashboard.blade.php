@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        /**
         * Datos de ejemplo estáticos solo para la vista base.
         * Cuando el backend esté conectado, el controlador debe inyectar estas variables.
         */
        $stats = $stats ?? [
            ['label' => 'Total recaudado (mes)',   'value' => '12,450.00', 'prefix' => 'S/', 'tone' => 'success', 'icon' => 'trending_up'],
            ['label' => 'Deuda pendiente',         'value' => '3,120.00',  'prefix' => 'S/', 'tone' => 'warning', 'icon' => 'warning'],
            ['label' => 'Usuarios registrados',    'value' => '854',       'prefix' => null, 'tone' => 'primary', 'icon' => 'group'],
            ['label' => 'Egresos (mes)',           'value' => '2,800.00',  'prefix' => 'S/', 'tone' => 'danger',  'icon' => 'trending_down'],
        ];

        $activities = $activities ?? [
            ['initials' => 'JP', 'tone' => 'primary', 'name' => 'Juan Pérez',   'action' => 'Registró pago #102',           'time' => 'Hace 5 min'],
            ['initials' => 'MG', 'tone' => 'warning', 'name' => 'María Gómez',  'action' => 'Actualizó padrón (Sector A)',  'time' => 'Hace 23 min'],
            ['initials' => 'CR', 'tone' => 'info',    'name' => 'Carlos Ruiz',  'action' => 'Generó multa por retraso',     'time' => 'Hace 1 hora'],
            ['initials' => 'AL', 'tone' => 'danger',  'name' => 'Ana López',    'action' => 'Registró egreso (Reparación)', 'time' => 'Hace 3 horas'],
        ];
    @endphp

    <x-ui.page-header
        title="Resumen General"
        subtitle="Vista consolidada del mes en curso." />

    <div class="row g-3 mb-4">
        @foreach ($stats as $stat)
            <div class="col-12 col-sm-6 col-xl-3">
                <x-ui.stat-card
                    :title="$stat['label']"
                    :value="$stat['value']"
                    :prefix="$stat['prefix']"
                    :tone="$stat['tone']"
                    :icon="$stat['icon']" />
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <x-ui.card title="Recaudación vs. Egresos (últimos 6 meses)" icon="bar_chart">
                <div class="text-body-secondary text-center py-5">
                    {{-- Placeholder para gráfico. Se conectará con Chart.js o similar. --}}
                    <x-ui.icon name="insert_chart" size="lg" class="mb-2 d-block mx-auto" />
                    <p class="mb-0">Gráfico pendiente de conexión con backend.</p>
                </div>
            </x-ui.card>
        </div>

        <div class="col-12 col-lg-4">
            <x-ui.card title="Accesos Rápidos" icon="bolt">
                <div class="d-flex flex-column gap-2">
                    <x-ui.quick-action icon="point_of_sale" :href="route('agua.cobros.index')">
                        Registrar Pago
                    </x-ui.quick-action>
                    <x-ui.quick-action icon="person_add" variant="outline" :href="route('agua.padron.index')">
                        Nuevo Usuario
                    </x-ui.quick-action>
                    <x-ui.quick-action icon="description" variant="outline" :href="route('agua.reportes-mensuales.index')">
                        Generar Reporte
                    </x-ui.quick-action>
                </div>
            </x-ui.card>
        </div>
    </div>

    <x-ui.card title="Actividad Reciente" icon="history">
        <div class="table-responsive">
            <table class="table agua-table mb-0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th class="text-end">Tiempo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $item)
                        <tr>
                            <td>
                                <div class="d-inline-flex align-items-center gap-2">
                                    <x-ui.avatar :initials="$item['initials']" :tone="$item['tone']" />
                                    <span class="fw-semibold">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="text-body-secondary">{{ $item['action'] }}</td>
                            <td class="text-end text-body-secondary">{{ $item['time'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
