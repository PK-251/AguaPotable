@extends('components.layouts.app')

@section('title', 'Nueva multa')

@section('content')
    <x-ui.page-header title="Registrar multa" subtitle="Montos fijos alineados con las resoluciones internas de la JASS." />

    <x-ui.form-errors :errors="$errors" />

    <form method="post" action="{{ route('agua.multas.store') }}" class="card">
        @csrf
        <div class="card-body">
            @include('agua.multas._form', ['multa' => null])
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('agua.multas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" data-cy="multa-guardar">Guardar</button>
        </div>
    </form>
@endsection
