@extends('components.layouts.guest')

@section('title', 'Error del servidor')

@section('content')
    <div class="agua-auth-card text-center">
        <div class="agua-auth__brand">
            <span class="agua-auth__brand-mark">
                <x-ui.icon name="error" />
            </span>
            <h1 class="agua-auth__title">500</h1>
            <p class="agua-auth__subtitle">Algo falló. Inténtalo de nuevo más tarde o contacta al operador.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary w-100">Ir al inicio</a>
    </div>
@endsection
