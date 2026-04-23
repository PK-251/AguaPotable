<?php

namespace App\Observers;

use App\Models\ReporteMensual;

class ReporteMensualObserver
{
    public function updated(ReporteMensual $reporteMensual): void
    {
        // Pendiente: estados aprobado/cerrado
    }
}
