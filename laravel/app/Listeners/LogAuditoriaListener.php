<?php

namespace App\Listeners;

use App\Events\Pagos\PagoRegistrado;
use App\Services\Agua\AuditoriaService;

class LogAuditoriaListener
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService,
    ) {
    }

    public function handle(PagoRegistrado $event): void
    {
        // Pendiente: registrar en ActivityLog vía AuditoriaService
    }
}
