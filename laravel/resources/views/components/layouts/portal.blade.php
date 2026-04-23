<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal — J.A.S.S. Quilcata')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <nav class="navbar navbar-expand-lg border-bottom bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('portal.home') }}">Agua — Mi cuenta</a>
        </div>
    </nav>
    <main class="container py-4">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
