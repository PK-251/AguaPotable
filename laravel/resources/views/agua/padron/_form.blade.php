@php
    /** @var \App\Models\PadronUsuario|null $residente */
    $residente = $residente ?? null;
    $esEdicion = $residente !== null;
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <x-ui.floating-input id="codigo" name="codigo" label="Código" :value="old('codigo', $residente?->codigo)" required />
    </div>
    <div class="col-md-4">
        <x-ui.floating-input id="nombre" name="nombre" label="Nombre" :value="old('nombre', $residente?->nombre)" required />
    </div>
    <div class="col-md-4">
        <x-ui.floating-input id="apellido" name="apellido" label="Apellido" :value="old('apellido', $residente?->apellido)" required />
    </div>
    <div class="col-12">
        <x-ui.floating-input id="direccion" name="direccion" label="Dirección" :value="old('direccion', $residente?->direccion)" required />
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                <option value="activo" @selected(old('estado', $residente?->estado) === 'activo')>Activo</option>
                <option value="cortado" @selected(old('estado', $residente?->estado) === 'cortado')>Cortado</option>
            </select>
            <label for="estado">Estado del servicio</label>
            @error('estado')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <select name="tarifa_id" id="tarifa_id" class="form-select @error('tarifa_id') is-invalid @enderror" required>
                <option value="">Seleccione tarifa</option>
                @foreach ($tarifas as $tarifa)
                    <option value="{{ $tarifa->id }}" @selected((string) old('tarifa_id', $residente?->tarifa_id) === (string) $tarifa->id)>
                        {{ $tarifa->nombre }} — S/ {{ number_format((float) $tarifa->monto, 2) }}
                    </option>
                @endforeach
            </select>
            <label for="tarifa_id">Tarifa aplicable</label>
            @error('tarifa_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
