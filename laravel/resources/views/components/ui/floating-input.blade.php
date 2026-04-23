{{--
    Floating input — Bootstrap 5.3 form-floating con soporte para toggle de contraseña.

    Uso:
        <x-ui.floating-input id="email" name="email" label="Correo" type="email" required />
        <x-ui.floating-input id="password" name="password" label="Contraseña" type="password" withPasswordToggle />
--}}
@props([
    'id'                 => '',
    'name'               => null,
    'label'              => '',
    'type'               => 'text',
    'value'              => null,
    'required'           => false,
    'withPasswordToggle' => false,
    'autocomplete'       => null,
])

@php
    $name = $name ?: $id;
    $value = $value ?? old($name);
    $hasError = $errors->has($name);
@endphp

<div class="form-floating position-relative">
    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        class="form-control {{ $hasError ? 'is-invalid' : '' }}"
        placeholder="{{ $label }}"
        @if ($required) required @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        {{ $attributes }}
    >
    <label for="{{ $id }}">{{ $label }}</label>

    @if ($withPasswordToggle)
        <button
            type="button"
            class="agua-password-toggle"
            aria-label="Mostrar u ocultar contraseña"
            data-agua-password-toggle="#{{ $id }}"
        >
            <x-ui.icon name="visibility" size="sm" />
        </button>
    @endif

    @if ($hasError)
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>
