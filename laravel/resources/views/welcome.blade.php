<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Agua Potable') }} — J.A.S.S. Quilcata</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @endif
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="h3">Sistema de gestión de pagos de agua potable</h1>
        <p class="text-body-secondary">Junta de Administración de Servicios de Saneamiento — Quilcata</p>
        <ul class="list-unstyled mt-4">
            @if (Route::has('login'))
                <li><a href="{{ route('login') }}">Ingreso operadores</a></li>
            @endif
            @if (Route::has('portal.home'))
                <li><a href="{{ route('portal.home') }}">Portal (vecinos)</a></li>
            @endif
        </ul>
    </div>
</body>
</html>
