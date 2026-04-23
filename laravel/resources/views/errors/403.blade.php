@extends('components.layouts.guest')

@section('title', 'Acceso denegado')

@section('content')
    <div class="agua-auth-card text-center">
        <div class="agua-auth__brand">
            <span class="agua-auth__brand-mark">
                <x-ui.icon name="block" />
            </span>
            <h1 class="agua-auth__title">403</h1>
            <p class="agua-auth__subtitle">No tienes permiso para ver este recurso.</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-100">Volver</a>
    </div>
@endsection
