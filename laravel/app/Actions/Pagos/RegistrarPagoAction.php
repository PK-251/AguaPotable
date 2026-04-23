<?php

namespace App\Actions\Pagos;

use App\Services\Agua\PagoService;

/**
 * Orquesta el registro de un pago; la lógica vive en PagoService.
 */
final class RegistrarPagoAction
{
    public function __construct(
        private readonly PagoService $pagoService,
    ) {
    }

    public function handle(): void
    {
        // Pendiente: parámetros DTO/Request y transacción en PagoService
    }
}
