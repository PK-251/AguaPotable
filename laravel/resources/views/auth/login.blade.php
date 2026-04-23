@extends('components.layouts.guest')

@section('title', 'Ingreso')

@section('content')
    <h1 class="h4 mb-3">Ingreso — operadores</h1>
    <x-form-errors :errors="$errors" />
    <form method="post" action="{{ route('login.store') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
    <p class="small text-body-secondary mt-3 mb-0">Pendiente: <code>Auth::attempt</code> y redirección al panel.</p>
@endsection
