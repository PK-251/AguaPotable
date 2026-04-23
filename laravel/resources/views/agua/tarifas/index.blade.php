@extends('components.layouts.app')

@section('title', 'Tarifas')

@section('content')
    @php
        $tarifas = $tarifas ?? [
            ['nombre' => 'Doméstica', 'descripcion' => 'Tarifa estándar para viviendas unifamiliares', 'monto' => '10.00', 'estado' => ['Activo',   'success'], 'vigencia' => '01 Ene, 2024'],
            ['nombre' => 'Comercial', 'descripcion' => 'Locales comerciales, tiendas y negocios',      'monto' => '25.00', 'estado' => ['Activo',   'success'], 'vigencia' => '01 Ene, 2024'],
            ['nombre' => 'Social',    'descripcion' => 'Casos especiales evaluados por la directiva',   'monto' => '5.00',  'estado' => ['Inactivo', 'muted'],   'vigencia' => '01 Jun, 2023'],
        ];
    @endphp

    <x-ui.page-header
        title="Gestión de Tarifas"
        subtitle="Administre los diferentes tipos de tarifas y sus montos aplicables.">
        <x-slot name="actions">
            <a href="#" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="add" size="sm" /> Nueva Tarifa
            </a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.data-table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="text-end">Monto (S/)</th>
                <th>Estado</th>
                <th>Fecha de vigencia</th>
                <th class="text-end" style="width:90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tarifas as $t)
                <tr>
                    <td class="fw-semibold">{{ $t['nombre'] }}</td>
                    <td class="text-body-secondary">{{ $t['descripcion'] }}</td>
                    <td class="text-end"><x-ui.money :amount="$t['monto']" currency="" /></td>
                    <td><x-ui.status-badge :label="$t['estado'][0]" :tone="$t['estado'][1]" /></td>
                    <td class="text-body-secondary">{{ $t['vigencia'] }}</td>
                    <td class="text-end">
                        <div class="agua-actions">
                            <a href="#" class="agua-icon-btn" aria-label="Editar">
                                <x-ui.icon name="edit" size="sm" />
                            </a>
                            <button type="button" class="agua-icon-btn agua-icon-btn--danger" aria-label="Eliminar">
                                <x-ui.icon name="delete" size="sm" />
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">Mostrando 1 a 3 de 3 registros</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
                </ul>
            </nav>
        </x-slot>
    </x-ui.data-table>
@endsection
