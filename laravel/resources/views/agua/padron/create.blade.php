@extends('components.layouts.app')

@section('title', 'Nuevo residente')

@section('content')
    <x-ui.page-header
        title="Registrar usuario en padrón"
        subtitle="Complete los datos del titular del servicio." />

    <x-ui.form-errors :errors="$errors" />

    <form method="post" action="{{ route('agua.padron.store') }}" class="card">
        @csrf
        <div class="card-body">
            @include('agua.padron._form', ['residente' => null, 'tarifas' => $tarifas])
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('agua.padron.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" data-cy="padron-guardar">Guardar</button>
        </div>
    </form>
@endsection
