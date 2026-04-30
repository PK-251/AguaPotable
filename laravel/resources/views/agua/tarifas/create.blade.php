@extends('components.layouts.app')

@section('title', 'Nueva tarifa')

@section('content')
    <x-ui.page-header title="Registrar tarifa" subtitle="Montos y fecha de vigencia según acuerdo de la JASS." />

    <x-ui.form-errors :errors="$errors" />

    <form method="post" action="{{ route('agua.tarifas.store') }}" class="card">
        @csrf
        <div class="card-body">
            @include('agua.tarifas._form', ['tarifa' => null])
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('agua.tarifas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" data-cy="tarifa-guardar">Guardar</button>
        </div>
    </form>
@endsection
