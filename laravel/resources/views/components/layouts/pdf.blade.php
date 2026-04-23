{{--
    Layout para documentos PDF (DomPDF).
    Inline el CSS de resources/css/pdf.css para evitar dependencias de asset pipeline.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Documento')</title>
    <style>
        {!! file_get_contents(resource_path('css/pdf.css')) !!}
    </style>
</head>
<body class="agua-pdf">
    @yield('content')
</body>
</html>
