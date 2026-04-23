<?php

namespace App\Actions\Reportes;

use App\Services\Agua\ReporteMensualService;

/**
 * Orquesta generación/cierre de reporte mensual; detalle en ReporteMensualService.
 */
final class GenerarReporteMensualAction
{
    public function __construct(
        private readonly ReporteMensualService $reporteMensualService,
    ) {
    }

    public function handle(): void
    {
        // Pendiente: periodo y criterio de cierre
    }
}
