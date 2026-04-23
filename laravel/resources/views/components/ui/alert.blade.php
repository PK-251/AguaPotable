@props(['variant' => 'info', 'dismissible' => false])
@php
    $class = match($variant) {
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'secondary' => 'alert-secondary',
        default => 'alert-info',
    };
@endphp
<div {{ $attributes->merge(['class' => 'alert '.$class.($dismissible ? ' alert-dismissible fade show' : '')]) }} role="alert">
    {{ $slot }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    @endif
</div>
