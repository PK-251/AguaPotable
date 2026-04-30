@extends('components.layouts.app')

@section('title', 'Editar residente')

@section('content')
    <x-ui.page-header
        title="Editar usuario del padrón"
        :subtitle="'Código '.$residente->codigo" />

    <x-ui.form-errors :errors="$errors" />

    <form method="post" action="{{ route('agua.padron.update', $residente) }}" class="card">
        @csrf
        @method('put')
        <div class="card-body">
            @include('agua.padron._form', ['residente' => $residente, 'tarifas' => $tarifas])
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('agua.padron.show', $residente) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" data-cy="padron-actualizar">Actualizar</button>
        </div>
    </form>
@endsection
