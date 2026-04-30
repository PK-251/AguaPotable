@extends('components.layouts.app')

@section('title', 'Editar tarifa')

@section('content')
    <x-ui.page-header :title="'Editar: '.$tarifa->nombre" subtitle="Actualice montos o vigencia con criterio administrativo." />

    <x-ui.form-errors :errors="$errors" />

    <form method="post" action="{{ route('agua.tarifas.update', $tarifa) }}" class="card">
        @csrf
        @method('put')
        <div class="card-body">
            @include('agua.tarifas._form', ['tarifa' => $tarifa])
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('agua.tarifas.show', $tarifa) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" data-cy="tarifa-actualizar">Actualizar</button>
        </div>
    </form>
@endsection
