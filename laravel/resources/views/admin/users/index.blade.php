@extends('components.layouts.app')

@section('title', 'Usuarios del sistema')

@section('content')
    <h1 class="h3 mb-3">Usuarios (operadores / admin)</h1>
    <p class="text-body-secondary">Pendiente: listado, roles y permisos.</p>
    <x-ui.table-empty message="Aún no hay datos." />
@endsection
