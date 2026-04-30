@extends('components.layouts.app')

@section('title', 'Aplicar multa')

@section('content')
    <x-ui.page-header
        title="Aplicar multa a usuario"
        subtitle="Asocia un concepto del catálogo a un mes determinado. Evita duplicados por usuario/concepto/mes." />

    <x-ui.form-errors :errors="$errors" />

    <form method="post" action="{{ route('agua.multas-usuario.store') }}" class="card">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Usuario del padrón</label>
                    <input type="hidden" name="padron_usuario_id" value="{{ old('padron_usuario_id', $padron?->id) }}">
                    @if ($padron)
                        <div class="border rounded-3 p-3 bg-body-secondary bg-opacity-10">
                            <div class="fw-semibold">{{ trim($padron->nombre.' '.$padron->apellido) }}</div>
                            <div class="text-body-secondary small">{{ $padron->codigo }} · {{ $padron->direccion }}</div>
                            <a href="{{ route('agua.multas-usuario.create') }}" class="small">Cambiar usuario</a>
                        </div>
                    @else
                        <x-ui.alert variant="warning" icon="warning">
                            Seleccione primero un usuario desde el padrón.
                        </x-ui.alert>
                        <a href="{{ route('agua.padron.index') }}" class="btn btn-outline-primary btn-sm">Ir al padrón</a>
                    @endif
                    @error('padron_usuario_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <select name="multa_id" id="multa_id" class="form-select @error('multa_id') is-invalid @enderror" required @unless($padron) disabled @endunless>
                            <option value="">Seleccione concepto</option>
                            @foreach ($multasActivas as $multa)
                                <option value="{{ $multa->id }}" @selected((string) old('multa_id') === (string) $multa->id)>
                                    {{ $multa->nombre }} — S/ {{ number_format((float) $multa->monto, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <label for="multa_id">Multa del catálogo</label>
                        @error('multa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="mes" class="form-label">Mes aplicado (YYYY-MM)</label>
                    <input
                        type="month"
                        name="mes"
                        id="mes"
                        class="form-control @error('mes') is-invalid @enderror"
                        value="{{ old('mes', now()->format('Y-m')) }}"
                        required
                        @unless($padron) disabled @endunless>
                    @error('mes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <x-ui.floating-input
                        id="monto"
                        name="monto"
                        label="Monto (opcional, por defecto el del catálogo)"
                        type="number"
                        step="0.01"
                        min="0"
                        :value="old('monto')" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('agua.multas-usuario.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" data-cy="multa-asignar-guardar" @unless($padron) disabled @endunless>
                Aplicar multa
            </button>
        </div>
    </form>
@endsection
