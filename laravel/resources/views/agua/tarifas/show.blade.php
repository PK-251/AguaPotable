@extends('components.layouts.app')

@section('title', 'Detalle tarifa')

@section('content')
    @if (session('status'))
        <x-ui.alert variant="success" icon="check_circle" class="mb-3">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.page-header :title="$tarifa->nombre" subtitle="Detalle de la tarifa usada en el padrón.">
        <x-slot name="actions">
            @if (auth()->user()->esAdmin())
                <a href="{{ route('agua.tarifas.edit', $tarifa) }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <x-ui.icon name="edit" size="sm" /> Editar
                </a>
            @endif
            <a href="{{ route('agua.tarifas.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="arrow_back" size="sm" /> Volver
            </a>
        </x-slot>
    </x-ui.page-header>

    <div class="row g-3">
        <div class="col-md-6">
            <x-ui.card title="Montos" icon="payments">
                <p class="h4 mb-2"><x-ui.money :amount="$tarifa->monto" /></p>
                <p class="text-body-secondary small mb-0">Referencia de cuota mensual según categoría.</p>
            </x-ui.card>
        </div>
        <div class="col-md-6">
            <x-ui.card title="Vigencia" icon="calendar_month">
                @php
                    $enVigor = $tarifa->vigente_desde && $tarifa->vigente_desde->lte(now()->startOfDay());
                @endphp
                <p class="mb-2"><strong>Desde:</strong> {{ $tarifa->vigente_desde?->format('d/m/Y') }}</p>
                <x-ui.status-badge
                    :label="$enVigor ? 'En vigor' : 'Programada'"
                    :tone="$enVigor ? 'success' : 'warning'" />
            </x-ui.card>
        </div>
        <div class="col-12">
            <x-ui.card title="Descripción" icon="description">
                <p class="mb-0 text-body-secondary">{{ $tarifa->descripcion ?: 'Sin descripción registrada.' }}</p>
            </x-ui.card>
        </div>
        <div class="col-12">
            <x-ui.card title="Uso en padrón" icon="group">
                <p class="mb-0">
                    <span class="fw-semibold">{{ $tarifa->padron_usuarios_count }}</span>
                    usuario(s) del padrón tienen esta tarifa asignada.
                </p>
            </x-ui.card>
        </div>
    </div>
@endsection
