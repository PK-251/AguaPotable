<?php

namespace App\Jobs;

use App\Models\ReporteMensual;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerarReporteMensualPdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ReporteMensual $reporteMensual,
    ) {
    }

    public function handle(): void
    {
        // Pendiente: reporte y plantilla PDF
    }
}
