@extends('components.layouts.guest')
@section('title', 'Error del servidor')
@section('content')
    <h1 class="h4">500</h1>
    <p>Algo falló. Intentá de nuevo más tarde o contactá al operador.</p>
    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Inicio</a>
@endsection
