@php
    /** @var \App\Models\Tarifa|null $tarifa */
    $tarifa = $tarifa ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <x-ui.floating-input id="nombre" name="nombre" label="Nombre" :value="old('nombre', $tarifa?->nombre)" required />
    </div>
    <div class="col-md-6">
        <x-ui.floating-input
            id="monto"
            name="monto"
            label="Monto (S/)"
            type="number"
            step="0.01"
            min="0"
            :value="old('monto', $tarifa?->monto)"
            required />
    </div>
    <div class="col-12">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $tarifa?->descripcion) }}</textarea>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="vigente_desde" class="form-label">Vigente desde</label>
        <input
            type="date"
            name="vigente_desde"
            id="vigente_desde"
            class="form-control @error('vigente_desde') is-invalid @enderror"
            value="{{ old('vigente_desde', $tarifa?->vigente_desde?->format('Y-m-d')) }}"
            required>
        @error('vigente_desde')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
