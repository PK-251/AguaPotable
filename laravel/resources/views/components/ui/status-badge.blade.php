{{--
    Status badge / chip con punto de color.

    Props:
      - label   (string)           — texto a mostrar.
      - tone    (string)           — 'success' | 'warning' | 'danger' | 'info' | 'muted' (default).
      - caps    (bool)             — variante en mayúsculas (ej. ACTIVO).
      - dotless (bool)             — oculta el punto (para chips informativos planos).
--}}
@props([
    'label'   => '',
    'tone'    => 'muted',
    'caps'    => false,
    'dotless' => false,
])

@php
    $toneClass = match ($tone) {
        'success' => 'agua-chip--success',
        'warning' => 'agua-chip--warning',
        'danger'  => 'agua-chip--danger',
        'info'    => 'agua-chip--info',
        default   => 'agua-chip--muted',
    };

    $classes = trim("agua-chip $toneClass"
        .($caps ? ' agua-chip--caps' : '')
        .($dotless ? ' agua-chip--no-dot' : ''));
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $label }}</span>
