@extends('components.layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="agua-auth-card">
        <div class="agua-auth__brand">
            <span class="agua-auth__brand-mark">
                <x-ui.icon name="water_drop" filled />
            </span>
            <h1 class="agua-auth__title">J.A.S.S. QUILCATA</h1>
            <p class="agua-auth__subtitle">Gestión de Agua Potable</p>
        </div>

        <x-ui.form-errors :errors="$errors" />

        <form method="POST" action="{{ route('login.store') }}" class="d-flex flex-column gap-3">
            @csrf

            <x-ui.floating-input
                id="email"
                name="email"
                label="Nombre de usuario o email"
                type="text"
                autocomplete="username"
                required
                :value="old('email')"
            />

            <x-ui.floating-input
                id="password"
                name="password"
                label="Contraseña"
                type="password"
                autocomplete="current-password"
                required
                withPasswordToggle
            />

            <div class="form-check">
                <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember">
                <label class="form-check-label small text-body-secondary" for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-primary py-2 d-flex align-items-center justify-content-center gap-2">
                <span>Iniciar sesión</span>
                <x-ui.icon name="login" size="sm" />
            </button>
        </form>

        <div class="agua-auth__footer">
            <a href="#" class="d-inline-flex align-items-center gap-1 text-primary">
                <x-ui.icon name="help" size="sm" />
                ¿Necesitas ayuda? Contacta al administrador
            </a>
        </div>
    </div>
@endsection
