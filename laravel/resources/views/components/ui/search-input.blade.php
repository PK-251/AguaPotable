{{--
    Search input con icono de lupa a la izquierda.

    Uso:
        <x-ui.search-input placeholder="Código o nombre..." name="q" :value="request('q')" />
--}}
@props([
    'placeholder' => 'Buscar...',
    'name'        => 'q',
    'value'       => null,
    'label'       => null,   // label opcional externo (para floating alternativo)
])

@if ($label)
    <div class="agua-search-group">
        <label class="form-label">{{ $label }}</label>
        <div class="agua-search">
            <x-ui.icon name="search" size="sm" class="agua-search__icon" />
            <input
                type="search"
                name="{{ $name }}"
                value="{{ $value ?? old($name) }}"
                class="form-control"
                placeholder="{{ $placeholder }}"
                {{ $attributes }}
            >
        </div>
    </div>
@else
    <div class="agua-search">
        <x-ui.icon name="search" size="sm" class="agua-search__icon" />
        <input
            type="search"
            name="{{ $name }}"
            value="{{ $value ?? old($name) }}"
            class="form-control"
            placeholder="{{ $placeholder }}"
            {{ $attributes }}
        >
    </div>
@endif
