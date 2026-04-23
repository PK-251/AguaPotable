<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel — Agua Potable J.A.S.S. Quilcata')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <nav class="navbar navbar-expand-lg border-bottom bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">J.A.S.S. Quilcata</a>
        </div>
    </nav>
    <main class="container py-4">
        @include('components.ui.breadcrumb', ['items' => $breadcrumbItems ?? []])
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
