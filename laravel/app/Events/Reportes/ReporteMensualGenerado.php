<?php

namespace App\Events\Reportes;

use App\Models\ReporteMensual;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReporteMensualGenerado
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public ReporteMensual $reporteMensual,
    ) {
    }
}
