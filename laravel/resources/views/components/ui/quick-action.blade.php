{{--
    Botón de acción rápida — ancho 100%, icono a la izquierda.
    Uso en "Accesos Rápidos" del dashboard.
--}}
@props([
    'icon'    => 'bolt',
    'href'    => '#',
    'variant' => 'primary',  // 'primary' o 'outline'
])

@php
    $btnClass = $variant === 'outline' ? 'btn-outline-secondary' : 'btn-primary';
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => "btn $btnClass w-100 justify-content-start d-flex align-items-center gap-2 py-2"]) }}>
    <x-ui.icon :name="$icon" size="sm" />
    <span>{{ $slot }}</span>
</a>
