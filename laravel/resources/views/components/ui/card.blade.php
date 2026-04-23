{{--
    Card — wrapper simple con header/footer opcionales.

    Uso:
        <x-ui.card title="Accesos Rápidos">
            ...contenido...
            <x-slot name="footer">...</x-slot>
        </x-ui.card>

    También:
        <x-ui.card>
            <x-slot name="header">HTML custom para encabezado</x-slot>
            ...
        </x-ui.card>
--}}
@props([
    'title'   => null,
    'icon'    => null,
    'padded'  => true,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if (isset($header) || $title)
        <div class="card-header bg-transparent border-bottom px-3 py-2 d-flex align-items-center gap-2">
            @if (isset($header))
                {{ $header }}
            @else
                @if ($icon)
                    <x-ui.icon :name="$icon" class="text-primary" />
                @endif
                <h2 class="h6 mb-0 fw-semibold">{{ $title }}</h2>
            @endif
        </div>
    @endif

    <div class="{{ $padded ? 'card-body' : '' }}">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="card-footer bg-transparent border-top">
            {{ $footer }}
        </div>
    @endif
</div>
