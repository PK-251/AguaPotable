@extends('components.layouts.app')

@section('title', 'Auditoría')

@section('content')
    @php
        $logs = $logs ?? [
            ['fecha' => '24 Oct 2023, 14:32:05', 'usuario' => 'admin_jass', 'modulo' => ['Tarifas',       'info'],     'accion' => 'Actualizó tarifa Doméstica (ID: TRF-02)', 'ip' => '192.168.1.105', 'danger' => false],
            ['fecha' => '24 Oct 2023, 10:15:44', 'usuario' => 'oper_juan',  'modulo' => ['Cobros',        'info'],     'accion' => 'Registró pago Recibo #4502',             'ip' => '10.0.0.5',      'danger' => false],
            ['fecha' => '23 Oct 2023, 16:45:12', 'usuario' => 'admin_jass', 'modulo' => ['Padrón',        'info'],     'accion' => 'Modificó datos de titular ID 892',       'ip' => '192.168.1.105', 'danger' => false],
            ['fecha' => '23 Oct 2023, 09:05:00', 'usuario' => 'sistema',    'modulo' => ['Sistema',       'muted'],    'accion' => 'Generación automática de recibos mensuales','ip' => 'localhost',     'danger' => false],
            ['fecha' => '22 Oct 2023, 11:20:33', 'usuario' => 'desconocido','modulo' => ['Autenticación','danger'],    'accion' => 'Intento fallido de inicio de sesión',    'ip' => '200.45.12.98',  'danger' => true],
            ['fecha' => '21 Oct 2023, 14:10:15', 'usuario' => 'oper_maria', 'modulo' => ['Cobros',        'info'],     'accion' => 'Anuló recibo #4489 (Error de digitación)','ip' => '10.0.0.6',      'danger' => false],
        ];
    @endphp

    <x-ui.page-header
        title="Auditoría de Sistema"
        subtitle="Registro inmutable de actividades y gestión de accesos.">
        <x-slot name="actions">
            <a href="#" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="download" size="sm" /> Exportar CSV
            </a>
        </x-slot>
    </x-ui.page-header>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active d-inline-flex align-items-center gap-1" href="{{ route('admin.audit.index') }}">
                <x-ui.icon name="fact_check" size="sm" /> Registro de auditoría
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-inline-flex align-items-center gap-1" href="{{ route('admin.users.index') }}">
                <x-ui.icon name="manage_accounts" size="sm" /> Usuarios internos
            </a>
        </li>
    </ul>

    <x-ui.data-table>
        <x-slot name="toolbar">
            <div class="flex-grow-1" style="max-width:360px;">
                <x-ui.search-input placeholder="Buscar por usuario, módulo o IP..." name="q" />
            </div>
            <select class="form-select" style="width:auto;">
                <option>Todos los módulos</option>
                <option>Padrón</option><option>Cobros</option><option>Tarifas</option>
                <option>Multas</option><option>Egresos</option>
            </select>
            <select class="form-select" style="width:auto;">
                <option>Últimos 7 días</option>
                <option>Último mes</option>
                <option>Últimos 3 meses</option>
            </select>
        </x-slot>

        <thead>
            <tr>
                <th>Fecha / Hora</th>
                <th>Usuario</th>
                <th>Módulo</th>
                <th>Acción realizada</th>
                <th>Dirección IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $l)
                <tr>
                    <td class="text-body-secondary">{{ $l['fecha'] }}</td>
                    <td class="{{ $l['danger'] ? 'text-danger fw-semibold' : '' }}">{{ $l['usuario'] }}</td>
                    <td><x-ui.status-badge :label="$l['modulo'][0]" :tone="$l['modulo'][1]" /></td>
                    <td class="{{ $l['danger'] ? 'text-danger' : '' }}">{{ $l['accion'] }}</td>
                    <td><code class="text-body-secondary">{{ $l['ip'] }}</code></td>
                </tr>
            @endforeach
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">Mostrando 1 a 6 de 1,245 registros</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                    <li class="page-item"><a class="page-link" href="#">&rsaquo;</a></li>
                </ul>
            </nav>
        </x-slot>
    </x-ui.data-table>
@endsection
