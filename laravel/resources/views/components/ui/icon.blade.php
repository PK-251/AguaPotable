{{--
    Icon — wrapper de Material Symbols Outlined.
    Uso:
        <x-ui.icon name="water_drop" />
        <x-ui.icon name="edit" size="sm" filled />
--}}
@props([
    'name'   => 'help',
    'size'   => null,   // null | 'sm' | 'lg'
    'filled' => false,
    'label'  => null,   // si se provee, el icono deja de ser decorativo
])

@php
    $classes = collect(['material-symbols-outlined'])
        ->when($size === 'sm', fn ($c) => $c->push('is-sm'))
        ->when($size === 'lg', fn ($c) => $c->push('is-lg'))
        ->when($filled, fn ($c) => $c->push('is-filled'))
        ->implode(' ');
@endphp

<span
    {{ $attributes->merge(['class' => $classes]) }}
    @if ($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
>{{ $name }}</span>
