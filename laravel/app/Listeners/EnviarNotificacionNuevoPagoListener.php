<?php

namespace App\Listeners;

use App\Events\Pagos\PagoRegistrado;

class EnviarNotificacionNuevoPagoListener
{
    public function handle(PagoRegistrado $event): void
    {
        // Pendiente: PagoRegistradoNotification o mail al vecino/operación interna
    }
}
