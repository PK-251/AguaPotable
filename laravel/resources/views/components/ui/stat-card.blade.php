{{--
    Stat card — tarjeta KPI.
    Se usa en dashboard, egresos y reportes.

    Props:
      - title  (string)          — etiqueta (se muestra en mayúsculas).
      - value  (string)          — valor principal.
      - prefix (string|null)     — prefijo del valor (ej. "S/").
      - help   (string|null)     — texto secundario / tendencia.
      - icon   (string|null)     — icono Material opcional a la derecha.
      - tone   (string|null)     — 'success' | 'warning' | 'danger' | 'primary' | 'filled'.
--}}
@props([
    'title'  => '',
    'value'  => '—',
    'prefix' => null,
    'help'   => null,
    'icon'   => null,
    'tone'   => null,
])

@php
    $toneClass = match ($tone) {
        'success' => 'agua-stat--success',
        'warning' => 'agua-stat--warning',
        'danger'  => 'agua-stat--danger',
        'primary' => 'agua-stat--primary',
        'filled'  => 'agua-stat--filled',
        default   => '',
    };
@endphp

<div {{ $attributes->merge(['class' => "agua-stat $toneClass"]) }}>
    <p class="agua-stat__label">{{ $title }}</p>
    <p class="agua-stat__value">
        @if ($prefix)
            <span class="agua-stat__value-prefix">{{ $prefix }}</span>
        @endif
        <span>{{ $value }}</span>
        @if ($icon)
            <x-ui.icon :name="$icon" class="agua-stat__value-icon" />
        @endif
    </p>
    @if ($help)
        <p class="agua-stat__trend">{{ $help }}</p>
    @endif
</div>
