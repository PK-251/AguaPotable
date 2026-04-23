@props(['errors' => null])
@if ($errors && $errors->any())
    <div class="alert alert-danger" role="alert">
        <p class="fw-semibold mb-1">Revisa el formulario:</p>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
