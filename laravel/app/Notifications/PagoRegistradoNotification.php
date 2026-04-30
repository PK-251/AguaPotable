<?php

namespace App\Notifications;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoRegistradoNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Pago $pago,
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        // Añadir 'database' tras migración notifications
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pago registrado - J.A.S.S. Quilcata')
            ->line('Pendiente: detalle de comprobante y referencia al pago registrado.');
    }
}
