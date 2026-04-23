{{--
    Alert reutilizable con variantes semánticas.
--}}
@props(['variant' => 'info', 'dismissible' => false, 'icon' => null])

@php
    $class = match ($variant) {
        'success' => 'alert-success',
        'danger'  => 'alert-danger',
        'warning' => 'alert-warning',
        'secondary' => 'alert-secondary',
        default   => 'alert-info',
    };
@endphp

<div {{ $attributes->merge(['class' => 'alert '.$class.($dismissible ? ' alert-dismissible fade show' : '')]) }}
     role="alert">
    @if ($icon)
        <x-ui.icon :name="$icon" class="me-2 align-middle" />
    @endif
    {{ $slot }}
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    @endif
</div>
