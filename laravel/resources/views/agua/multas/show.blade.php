@extends('components.layouts.app')

@section('title', 'Detalle multa')

@section('content')
    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.page-header :title="$multa->nombre" subtitle="Referencia del catálogo de multas.">
        <x-slot name="actions">
            @if (auth()->user()->esAdmin())
                <a href="{{ route('agua.multas.edit', $multa) }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <x-ui.icon name="edit" size="sm" /> Editar
                </a>
            @endif
            <a href="{{ route('agua.multas.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="arrow_back" size="sm" /> Volver
            </a>
        </x-slot>
    </x-ui.page-header>

    <div class="row g-3">
        <div class="col-md-6">
            <x-ui.card title="Monto referencial" icon="payments">
                <p class="h4 mb-0"><x-ui.money :amount="$multa->monto" /></p>
            </x-ui.card>
        </div>
        <div class="col-md-6">
            <x-ui.card title="Estado" icon="toggle_on">
                <x-ui.status-badge
                    :label="$multa->activa ? 'Activa' : 'Inactiva'"
                    :tone="$multa->activa ? 'success' : 'muted'" />
                @if (! $multa->activa)
                    <p class="text-body-secondary small mt-2 mb-0">No puede asignarse a nuevos usuarios hasta reactivarla.</p>
                @endif
            </x-ui.card>
        </div>
        <div class="col-12">
            <x-ui.card title="Descripción" icon="description">
                <p class="mb-0 text-body-secondary">{{ $multa->descripcion ?: 'Sin descripción.' }}</p>
            </x-ui.card>
        </div>
        <div class="col-12">
            <x-ui.card title="Asignaciones" icon="group">
                <p class="mb-0">
                    Esta multa tiene <span class="fw-semibold">{{ $multa->multas_usuario_count }}</span> registro(s) aplicado(s) en el padrón.
                </p>
            </x-ui.card>
        </div>
    </div>
@endsection
