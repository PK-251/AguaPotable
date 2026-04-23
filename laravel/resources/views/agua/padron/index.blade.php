@extends('components.layouts.app')

@section('title', 'Padrón de usuarios')

@section('content')
    @php
        /** Datos demo estáticos para la vista base. */
        $rows = $rows ?? [
            ['codigo' => 'U-1001', 'initials' => 'CQ', 'tone' => 'muted',   'nombre' => 'Carlos Quispe Mamani',   'direccion' => 'Sector Central Mz A Lt 4',     'tarifa' => 'Doméstica', 'estado' => ['Activo',   'success'], 'deuda' => '0.00',  'deudaTone' => null],
            ['codigo' => 'U-1002', 'initials' => 'MR', 'tone' => 'muted',   'nombre' => 'María Rodriguez Soto',   'direccion' => 'Barrio Alto C. Principal 120',  'tarifa' => 'Comercial', 'estado' => ['Cortado',  'danger'],  'deuda' => '45.50', 'deudaTone' => 'danger'],
            ['codigo' => 'U-1003', 'initials' => 'JL', 'tone' => 'warning', 'nombre' => 'Juan Lopez Flores',      'direccion' => 'Sector Sur Mz C Lt 12',         'tarifa' => 'Doméstica', 'estado' => ['Activo',   'success'], 'deuda' => '15.00', 'deudaTone' => null],
            ['codigo' => 'U-1004', 'initials' => 'AT', 'tone' => 'muted',   'nombre' => 'Ana Torres Huaman',      'direccion' => 'Barrio Nuevo Psj. Sol 45',      'tarifa' => 'Social',    'estado' => ['Inactivo', 'muted'],   'deuda' => '0.00',  'deudaTone' => null],
            ['codigo' => 'U-1005', 'initials' => 'PG', 'tone' => 'muted',   'nombre' => 'Pedro Gomez Ruiz',       'direccion' => 'Sector Central Mz B Lt 2',      'tarifa' => 'Doméstica', 'estado' => ['Activo',   'success'], 'deuda' => '0.00',  'deudaTone' => null],
        ];
    @endphp

    <x-ui.page-header
        title="Padrón de Usuarios"
        subtitle="Gestión y registro de residentes activos e inactivos.">
        <x-slot name="actions">
            <a href="#" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="download" size="sm" /> Exportar
            </a>
            <a href="#" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="add" size="sm" /> Nuevo Residente
            </a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.filter-bar>
        <x-ui.search-input label="Buscar" placeholder="Código o nombre..." name="q" :value="request('q')" />

        <div>
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
                <option>Todos</option>
                <option>Activo</option>
                <option>Cortado</option>
                <option>Inactivo</option>
            </select>
        </div>

        <div>
            <label class="form-label">Tarifa</label>
            <select class="form-select" name="tarifa">
                <option>Todas</option>
                <option>Doméstica</option>
                <option>Comercial</option>
                <option>Social</option>
            </select>
        </div>
    </x-ui.filter-bar>

    <x-ui.data-table>
        <thead>
            <tr>
                <th style="width:110px;">Código</th>
                <th>Nombre completo</th>
                <th>Dirección</th>
                <th>Tarifa</th>
                <th>Estado</th>
                <th class="text-end">Deuda actual</th>
                <th class="text-end" style="width:90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $r)
                <tr>
                    <td class="text-body-secondary">{{ $r['codigo'] }}</td>
                    <td>
                        <div class="d-inline-flex align-items-center gap-2">
                            <x-ui.avatar :initials="$r['initials']" :tone="$r['tone']" />
                            <span>{{ $r['nombre'] }}</span>
                        </div>
                    </td>
                    <td class="text-body-secondary">{{ $r['direccion'] }}</td>
                    <td>{{ $r['tarifa'] }}</td>
                    <td><x-ui.status-badge :label="$r['estado'][0]" :tone="$r['estado'][1]" /></td>
                    <td class="text-end">
                        <x-ui.money :amount="$r['deuda']" :tone="$r['deudaTone']" />
                    </td>
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
            @empty
                <tr><td colspan="7"><x-ui.empty-state icon="group_off" title="Sin residentes" message="Registre el primer residente para comenzar." /></td></tr>
            @endforelse
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">Mostrando 1–5 de 854 residentes</div>
            <nav aria-label="Paginación">
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
