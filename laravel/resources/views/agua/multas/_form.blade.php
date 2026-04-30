@php
    /** @var \App\Models\Multa|null $multa */
    $multa = $multa ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <x-ui.floating-input id="nombre" name="nombre" label="Nombre / concepto" :value="old('nombre', $multa?->nombre)" required />
    </div>
    <div class="col-md-6">
        <x-ui.floating-input
            id="monto"
            name="monto"
            label="Monto referencial (S/)"
            type="number"
            step="0.01"
            min="0"
            :value="old('monto', $multa?->monto)"
            required />
    </div>
    <div class="col-12">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $multa?->descripcion) }}</textarea>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        @php
            $activaSeleccion = old('activa', $multa ? ($multa->activa ? '1' : '0') : '1');
        @endphp
        <div class="form-floating">
            <select name="activa" id="activa" class="form-select @error('activa') is-invalid @enderror" required>
                <option value="1" @selected((string) $activaSeleccion === '1')>Activa (asignable)</option>
                <option value="0" @selected((string) $activaSeleccion === '0')>Inactiva</option>
            </select>
            <label for="activa">Estado en catálogo</label>
            @error('activa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
