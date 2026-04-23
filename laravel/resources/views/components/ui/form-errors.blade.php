{{--
    Form errors summary.
    Uso: <x-ui.form-errors :errors="$errors" />
--}}
@props(['errors' => null])

@if ($errors && $errors->any())
    <x-ui.alert variant="danger" icon="error">
        <p class="fw-semibold mb-1">Revisa el formulario:</p>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-ui.alert>
@endif
