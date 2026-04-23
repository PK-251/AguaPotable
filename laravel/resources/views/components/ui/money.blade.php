{{--
    Money — formatea un monto con prefijo de moneda.
    Acepta números o strings previamente formateados.

    Props:
      - amount   (numeric|string)  — monto (si es numeric, se formatea con 2 decimales).
      - currency (string)          — símbolo de moneda (default "S/").
      - tone     (string|null)     — 'success' | 'warning' | 'danger' | 'primary'.
      - size     (string|null)     — 'lg' aumenta la tipografía.
--}}
@props([
    'amount'   => 0,
    'currency' => 'S/',
    'tone'     => null,
    'size'     => null,
])

@php
    $formatted = is_numeric($amount) ? number_format((float) $amount, 2) : $amount;

    $toneClass = match ($tone) {
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger'  => 'text-danger',
        'primary' => 'text-primary',
        default   => '',
    };

    $sizeClass = $size === 'lg' ? 'fs-4 fw-bold' : '';
    $classes = trim("agua-money $toneClass $sizeClass");
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    <span class="agua-money__currency">{{ $currency }}</span>
    <span class="agua-money__value">{{ $formatted }}</span>
</span>
