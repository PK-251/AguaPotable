@extends('components.layouts.app')

@section('title', 'Usuarios internos')

@section('content')
    @php
        $users = $users ?? [
            ['initials' => 'AJ', 'tone' => 'primary', 'nombre' => 'Administrador JASS', 'email' => 'admin@jass-quilcata.pe', 'rol' => ['Administrador', 'info'],  'estado' => ['Activo', 'success'], 'ultimo' => 'Hoy, 09:12'],
            ['initials' => 'JP', 'tone' => 'success', 'nombre' => 'Juan Pérez',         'email' => 'juan@jass-quilcata.pe',  'rol' => ['Operador',      'muted'], 'estado' => ['Activo', 'success'], 'ultimo' => 'Ayer, 16:45'],
            ['initials' => 'MG', 'tone' => 'warning', 'nombre' => 'María Gómez',        'email' => 'maria@jass-quilcata.pe', 'rol' => ['Operador',      'muted'], 'estado' => ['Activo', 'success'], 'ultimo' => 'Ayer, 08:20'],
        ];
    @endphp

    <x-ui.page-header
        title="Usuarios internos"
        subtitle="Gestión de cuentas de administradores y operadores del sistema.">
        <x-slot name="actions">
            <a href="#" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="person_add" size="sm" /> Nuevo usuario
            </a>
        </x-slot>
    </x-ui.page-header>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link d-inline-flex align-items-center gap-1" href="{{ route('admin.audit.index') }}">
                <x-ui.icon name="fact_check" size="sm" /> Registro de auditoría
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active d-inline-flex align-items-center gap-1" href="{{ route('admin.users.index') }}">
                <x-ui.icon name="manage_accounts" size="sm" /> Usuarios internos
            </a>
        </li>
    </ul>

    <x-ui.data-table>
        <x-slot name="toolbar">
            <div class="flex-grow-1" style="max-width:360px;">
                <x-ui.search-input placeholder="Nombre o correo..." name="q" />
            </div>
            <select class="form-select" style="width:auto;">
                <option>Todos los roles</option>
                <option>Administrador</option>
                <option>Operador</option>
            </select>
        </x-slot>

        <thead>
            <tr>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Último acceso</th>
                <th class="text-end" style="width:90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $u)
                <tr>
                    <td>
                        <div class="d-inline-flex align-items-center gap-2">
                            <x-ui.avatar :initials="$u['initials']" :tone="$u['tone']" />
                            <span class="fw-semibold">{{ $u['nombre'] }}</span>
                        </div>
                    </td>
                    <td class="text-body-secondary">{{ $u['email'] }}</td>
                    <td><x-ui.status-badge :label="$u['rol'][0]" :tone="$u['rol'][1]" /></td>
                    <td><x-ui.status-badge :label="$u['estado'][0]" :tone="$u['estado'][1]" /></td>
                    <td class="text-body-secondary">{{ $u['ultimo'] }}</td>
                    <td class="text-end">
                        <div class="agua-actions">
                            <a href="#" class="agua-icon-btn" aria-label="Editar"><x-ui.icon name="edit" size="sm" /></a>
                            <button type="button" class="agua-icon-btn agua-icon-btn--danger" aria-label="Desactivar"><x-ui.icon name="block" size="sm" /></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-ui.data-table>
@endsection
