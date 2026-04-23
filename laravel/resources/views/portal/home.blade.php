@extends('components.layouts.portal')

@section('title', 'Inicio')

@section('content')
    @php
        $user = $user ?? (object) ['name' => 'Juan Pérez'];
    @endphp

    <header class="mb-4">
        <h1 class="h3 fw-bold mb-1">Bienvenido, {{ $user->name }}</h1>
        <p class="text-body-secondary mb-0">Consulta tu estado de cuenta y gestiona tus pagos de manera rápida y segura.</p>
    </header>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <x-ui.card padded>
                <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                    <span class="text-body-secondary small fw-bold text-uppercase" style="letter-spacing:0.05em;">Resumen de Cuenta</span>
                    <x-ui.status-badge label="Estado del servicio: Activo" tone="success" />
                </div>

                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-6">
                        <p class="text-body-secondary mb-1 small">Deuda pendiente</p>
                        <p class="display-6 fw-bold text-danger mb-0">S/ 25.00</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded border d-flex align-items-center gap-2">
                            <x-ui.icon name="event" class="text-primary" />
                            <div>
                                <div class="small text-body-secondary">Próximo vencimiento</div>
                                <div class="fw-semibold">30 Jun 2024</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="col-12 col-lg-4">
            <x-ui.card title="Acciones rápidas" icon="bolt">
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('portal.estado-cuenta') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                        <x-ui.icon name="receipt_long" size="sm" /> Ver estado de cuenta
                    </a>
                    <a href="{{ route('portal.comprobantes.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
                        <x-ui.icon name="download" size="sm" /> Descargar último comprobante
                    </a>
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="agua-info-panel mt-3">
        <span class="agua-info-panel__icon">
            <x-ui.icon name="info" />
        </span>
        <div>
            <p class="agua-info-panel__title">Información de pago</p>
            <p class="agua-info-panel__body">
                Puede acercarse a la oficina principal de lunes a viernes de 8:00 AM a 4:00 PM,
                o realizar una transferencia bancaria a la cuenta BCP: <strong>193-0000000-0-00</strong>.
                Recuerde enviar su voucher al WhatsApp de atención al cliente.
            </p>
        </div>
    </div>
@endsection
