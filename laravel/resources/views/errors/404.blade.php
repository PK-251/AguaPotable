@extends('components.layouts.guest')

@section('title', 'No encontrado')

@section('content')
    <div class="agua-auth-card text-center">
        <div class="agua-auth__brand">
            <span class="agua-auth__brand-mark">
                <x-ui.icon name="travel_explore" />
            </span>
            <h1 class="agua-auth__title">404</h1>
            <p class="agua-auth__subtitle">El recurso no existe o fue movido.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary w-100">Volver al inicio</a>
    </div>
@endsection
