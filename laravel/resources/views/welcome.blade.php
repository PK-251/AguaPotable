@extends('components.layouts.guest')

@section('title', 'J.A.S.S. QUILCATA')

@section('content')
    <div class="agua-auth-card text-center">
        <div class="agua-auth__brand">
            <span class="agua-auth__brand-mark">
                <x-ui.icon name="water_drop" filled />
            </span>
            <h1 class="agua-auth__title">J.A.S.S. QUILCATA</h1>
            <p class="agua-auth__subtitle">Gestión de agua potable</p>
        </div>

        <div class="d-flex flex-column gap-2">
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                    <x-ui.icon name="login" size="sm" /> Ingreso operadores
                </a>
            @endif
            @if (Route::has('portal.home'))
                <a href="{{ route('portal.home') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
                    <x-ui.icon name="person" size="sm" /> Portal de vecinos
                </a>
            @endif
        </div>
    </div>
@endsection
