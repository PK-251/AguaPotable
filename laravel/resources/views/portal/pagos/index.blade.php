@extends('components.layouts.portal')

@section('title', 'Pagos — portal')

@section('content')
    <h1 class="h3 mb-3">Pagos</h1>
    <p class="text-body-secondary">Pendiente: historial (solo referencia, lectura en portal).</p>
    <x-ui.table-empty message="Aún no hay datos." />
@endsection
