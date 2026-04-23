@extends('components.layouts.guest')
@section('title', 'Prohibido')
@section('content')
    <h1 class="h4">403</h1>
    <p>No tenés permiso para ver este recurso.</p>
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">Volver</a>
@endsection
