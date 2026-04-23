<?php

namespace App\Notifications;

use App\Models\ReporteMensual;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CierreMensualListoNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ReporteMensual $reporteMensual,
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cierre mensual listo - J.A.S.S. Quilcata')
            ->line('Pendiente: enlace o adjunto al reporte aprobado.');
    }
}
