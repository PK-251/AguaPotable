{{--
    Empty state — más expresivo que table-empty.

    Uso:
        <x-ui.empty-state icon="group_off" title="Aún no hay residentes"
            message="Registre el primer residente para comenzar." />
--}}
@props([
    'icon'    => 'inbox',
    'title'   => 'Sin datos para mostrar',
    'message' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-5 px-3']) }}>
    <div class="d-inline-flex align-items-center justify-content-center mb-3"
         style="width:56px;height:56px;border-radius:50%;background-color:var(--agua-surface-alt);color:var(--agua-text-subtle);">
        <x-ui.icon :name="$icon" size="lg" />
    </div>
    <p class="fw-semibold mb-1">{{ $title }}</p>
    @if ($message)
        <p class="text-body-secondary mb-0">{{ $message }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-3">{{ $slot }}</div>
    @endif
</div>
