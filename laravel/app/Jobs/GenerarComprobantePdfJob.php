<?php

namespace App\Jobs;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerarComprobantePdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Pago $pago,
    ) {
    }

    public function handle(): void
    {
        // Pendiente: ComprobanteService + DomPDF
    }
}
