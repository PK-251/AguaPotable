<?php

namespace App\Observers;

use App\Models\Pago;

class PagoObserver
{
    public function created(Pago $pago): void
    {
        // Pendiente: eventos/auditoría si aplica
    }
}
