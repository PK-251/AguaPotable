{{--
    Page header — título + subtítulo + acciones a la derecha.

    Uso:
        <x-ui.page-header title="Padrón de Usuarios" subtitle="Gestión y registro...">
            <x-slot name="actions">
                <a href="#" class="btn btn-outline-secondary"><x-ui.icon name="download" /> Exportar</a>
                <a href="#" class="btn btn-primary"><x-ui.icon name="add" /> Nuevo Residente</a>
            </x-slot>
        </x-ui.page-header>
--}}
@props(['title' => '', 'subtitle' => null])

<div class="agua-page-header">
    <div>
        <h1 class="agua-page-header__title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="agua-page-header__subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    @if (isset($actions))
        <div class="agua-page-header__actions">
            {{ $actions }}
        </div>
    @endif
</div>
