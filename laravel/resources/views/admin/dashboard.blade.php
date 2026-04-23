@extends('components.layouts.app')

@section('title', 'Panel principal')

@section('content')
    <h1 class="h3 mb-3">Panel</h1>
    <p class="text-body-secondary">Pendiente: KPI, cobro del mes, deuda y atajos al padrón.</p>
    <div class="row g-3">
        <div class="col-md-3">
            <x-ui.stat-card title="Padrón" value="—" help="A definir" />
        </div>
    </div>
@endsection
