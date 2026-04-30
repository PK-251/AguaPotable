@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $estado = $resumen->estadoCuentas;
    @endphp

    <x-ui.page-header
        title="Resumen general"
        :subtitle="'Periodo en curso: '.$resumen->periodoReferencia.'. Indicadores en tiempo real desde la base de datos.'" />

    <div class="row g-3 mb-4">
        @foreach ($resumen->tarjetasKpi as $stat)
            <div class="col-12 col-sm-6 col-xl-3">
                <x-ui.stat-card
                    :title="$stat['label']"
                    :value="$stat['value']"
                    :prefix="$stat['prefix']"
                    :tone="$stat['tone']"
                    :icon="$stat['icon']"
                    :help="$stat['help'] ?? null" />
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <x-ui.card title="Pagos recientes" icon="payments">
                @if (count($resumen->pagosRecientes) === 0)
                    <x-ui.empty-state
                        icon="receipt_long"
                        title="Sin movimientos registrados"
                        message="Cuando se registren cobros aparecerán aquí los últimos movimientos." />
                @else
                    <div class="table-responsive">
                        <table class="table agua-table mb-0">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Periodo</th>
                                    <th class="text-end">Monto</th>
                                    <th>Estado</th>
                                    <th class="d-none d-md-table-cell">Registrado por</th>
                                    <th class="text-end d-none d-lg-table-cell">Cuándo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resumen->pagosRecientes as $pago)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $pago['codigo_usuario'] }}</div>
                                            <div class="text-body-secondary small">{{ $pago['nombre_usuario'] }}</div>
                                        </td>
                                        <td>{{ $pago['periodo'] }}</td>
                                        <td class="text-end text-nowrap">S/ {{ $pago['monto_total'] }}</td>
                                        <td>
                                            <x-ui.status-badge
                                                :tone="$pago['estado'] === 'pagado' ? 'success' : 'warning'"
                                                :label="$pago['estado_etiqueta']" />
                                        </td>
                                        <td class="d-none d-md-table-cell text-body-secondary">{{ $pago['registrado_por'] }}</td>
                                        <td class="text-end d-none d-lg-table-cell text-body-secondary">{{ $pago['fecha_humana'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-ui.card>
        </div>

        <div class="col-12 col-lg-4">
            <x-ui.card title="Accesos rápidos" icon="bolt">
                <div class="d-flex flex-column gap-2">
                    <x-ui.quick-action icon="group" :href="route('agua.padron.index')">
                        Padrón de usuarios
                    </x-ui.quick-action>
                    <x-ui.quick-action icon="point_of_sale" variant="outline" :href="route('agua.cobros.index')">
                        Cobros
                    </x-ui.quick-action>
                    <x-ui.quick-action icon="sell" variant="outline" :href="route('agua.tarifas.index')">
                        Tarifas
                    </x-ui.quick-action>
                    <x-ui.quick-action icon="warning" variant="outline" :href="route('agua.multas.index')">
                        Multas
                    </x-ui.quick-action>
                    <x-ui.quick-action icon="receipt_long" variant="outline" :href="route('agua.egresos.index')">
                        Egresos
                    </x-ui.quick-action>
                    <x-ui.quick-action icon="insert_chart" variant="outline" :href="route('agua.reportes-mensuales.index')">
                        Reportes mensuales
                    </x-ui.quick-action>

                    @if ($resumen->esAdministrador)
                        <hr class="my-2">
                        <p class="small text-body-secondary mb-1">Administración</p>
                        <x-ui.quick-action icon="fact_check" variant="outline" :href="route('admin.audit.index')">
                            Auditoría
                        </x-ui.quick-action>
                        <x-ui.quick-action icon="manage_accounts" variant="outline" :href="route('admin.users.index')">
                            Usuarios del sistema
                        </x-ui.quick-action>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <x-ui.card title="Estado de cuentas (padrón activo)" icon="pie_chart">
                <p class="text-body-secondary small mb-3">
                    {{ $estado['nota'] }}
                </p>
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-3 h-100 bg-body-secondary bg-opacity-10">
                            <p class="small text-uppercase text-body-secondary mb-1">Al día</p>
                            <p class="h4 mb-0">{{ number_format($estado['al_dia']) }}</p>
                            <p class="small text-body-secondary mb-0">Sin cobros pendientes ni multas impagas</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-3 h-100">
                            <p class="small text-uppercase text-body-secondary mb-1">Con deuda</p>
                            <p class="h4 mb-0 text-warning">{{ number_format($estado['con_deuda']) }}</p>
                            <p class="small text-body-secondary mb-0">Al menos un cobro pendiente</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-3 h-100">
                            <p class="small text-uppercase text-body-secondary mb-1">Con multa</p>
                            <p class="h4 mb-0 text-danger">{{ number_format($estado['con_multa']) }}</p>
                            <p class="small text-body-secondary mb-0">Multa asignada sin pagar</p>
                        </div>
                    </div>
                </div>
                <p class="small text-body-secondary mt-3 mb-0">
                    Usuarios activos en padrón:
                    <span class="fw-semibold">{{ number_format($estado['usuarios_activos']) }}</span>
                </p>
            </x-ui.card>
        </div>

        <div class="col-12 col-xl-6">
            <x-ui.card title="Alertas operativas" icon="notifications_active">
                @if (count($resumen->alertas) === 0)
                    <x-ui.empty-state
                        icon="task_alt"
                        title="Sin alertas por ahora"
                        message="No se detectaron situaciones que requieran atención inmediata según los datos actuales." />
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach ($resumen->alertas as $alerta)
                            <x-ui.alert :variant="$alerta['variante']" :icon="$alerta['icon'] ?? null">
                                <strong class="d-block">{{ $alerta['titulo'] }}</strong>
                                <span class="d-block small">{{ $alerta['mensaje'] }}</span>
                            </x-ui.alert>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>

    <x-ui.card title="Actividad reciente" icon="history">
        @if (count($resumen->actividades) === 0)
            <x-ui.empty-state
                icon="history"
                title="Sin registros de auditoría"
                message="Las acciones relevantes del sistema aparecerán aquí cuando comiencen a registrarse en el libro de auditoría." />
        @else
            <div class="table-responsive">
                <table class="table agua-table mb-0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Módulo</th>
                            <th class="text-end">Tiempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resumen->actividades as $item)
                            <tr>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <x-ui.avatar :initials="$item['iniciales']" :tone="$item['tone']" />
                                        <span class="fw-semibold">{{ $item['usuario'] }}</span>
                                    </div>
                                </td>
                                <td class="text-body-secondary">{{ $item['accion'] }}</td>
                                <td><span class="badge text-bg-light">{{ $item['modulo'] }}</span></td>
                                <td class="text-end text-body-secondary">{{ $item['tiempo'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-ui.card>
@endsection
