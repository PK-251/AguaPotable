@extends('components.layouts.app')

@section('title', 'Editar multa')

@section('content')
    <x-ui.page-header :title="'Editar: '.$multa->nombre" />

    <x-ui.form-errors :errors="$errors" />

    <form method="post" action="{{ route('agua.multas.update', $multa) }}" class="card">
        @csrf
        @method('put')
        <div class="card-body">
            @include('agua.multas._form', ['multa' => $multa])
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('agua.multas.show', $multa) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" data-cy="multa-actualizar">Actualizar</button>
        </div>
    </form>
@endsection
