{{--
    Avatar circular con iniciales o imagen.

    Props:
      - initials (string)    — 1-2 letras.
      - src      (string?)   — URL de imagen (opcional).
      - size     (int)       — diámetro en pixeles (default 36).
      - tone     (string)    — 'primary' | 'success' | 'warning' | 'danger' | 'muted' (default)
--}}
@props([
    'initials' => 'A',
    'src'      => null,
    'size'     => 36,
    'tone'     => 'primary',
])

@php
    $bg = match ($tone) {
        'success' => 'var(--agua-success-soft)',
        'warning' => 'var(--agua-warning-soft)',
        'danger'  => 'var(--agua-danger-soft)',
        'muted'   => 'var(--agua-surface-alt)',
        default   => 'var(--agua-primary-soft)',
    };
    $color = match ($tone) {
        'success' => 'var(--agua-success)',
        'warning' => 'var(--agua-warning)',
        'danger'  => 'var(--agua-danger)',
        'muted'   => 'var(--agua-text-muted)',
        default   => 'var(--agua-primary)',
    };
    $style = "width:{$size}px;height:{$size}px;background-color:{$bg};color:{$color};"
        .'border-radius:50%;display:inline-flex;align-items:center;justify-content:center;'
        .'font-weight:600;font-size:'.max(10, (int) ($size / 2.6)).'px;overflow:hidden;flex-shrink:0;';
@endphp

<span {{ $attributes->merge(['class' => 'agua-avatar', 'style' => $style]) }}>
    @if ($src)
        <img src="{{ $src }}" alt="" style="width:100%;height:100%;object-fit:cover;">
    @else
        {{ Str::upper(Str::substr(trim($initials), 0, 2)) }}
    @endif
</span>
