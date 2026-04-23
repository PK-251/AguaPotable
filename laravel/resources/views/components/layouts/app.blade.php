{{--
    Layout administrativo (admin + módulos "agua").
    Shell con sidebar fijo, topbar con breadcrumb/buscador/acciones, y área principal.

    Uso:
        @extends('components.layouts.app')
        @section('title', 'Padrón de usuarios')
        @section('page-title', 'Padrón de Usuarios')       // opcional, default = title
        @section('content') ... @endsection
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — J.A.S.S. QUILCATA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0..1,0&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <div class="agua-shell">
        <div class="agua-shell__body">

            @include('components.admin.sidebar')

            <div class="agua-sidebar__backdrop" data-agua-sidebar-backdrop></div>

            <div class="agua-main">
                @include('components.admin.topbar')

                <main class="agua-main__inner">
                    @includeWhen(isset($breadcrumbItems) && !empty($breadcrumbItems),
                        'components.ui.breadcrumb', ['items' => $breadcrumbItems ?? []])

                    @yield('content')
                </main>

                @include('components.admin.footer')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
