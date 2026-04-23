@extends('components.layouts.app')

@section('title', 'Importación / padrón')

@section('content')
    <h1 class="h3 mb-3">Importación de padrón</h1>
    <p class="text-body-secondary">Pendiente: carga, validación y job en cola.</p>
    <x-ui.table-empty message="Aún no hay formulario de carga." />
@endsection
