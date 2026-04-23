@extends('components.layouts.app')

@section('title', 'Auditoría')

@section('content')
    <h1 class="h3 mb-3">Registro de actividad</h1>
    <p class="text-body-secondary">Pendiente: listado con filtros sobre <code>activity_logs</code>.</p>
    <x-ui.table-empty message="Aún no hay búsqueda implementada." />
@endsection
