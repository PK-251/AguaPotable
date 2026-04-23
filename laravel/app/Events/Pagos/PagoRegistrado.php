<?php

namespace App\Events\Pagos;

use App\Models\Pago;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PagoRegistrado
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Pago $pago,
    ) {
    }
}
