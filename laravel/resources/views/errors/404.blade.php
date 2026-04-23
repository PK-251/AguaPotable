@extends('components.layouts.guest')
@section('title', 'No encontrado')
@section('content')
    <h1 class="h4">404</h1>
    <p>El recurso no existe o fue movido.</p>
    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Inicio</a>
@endsection
