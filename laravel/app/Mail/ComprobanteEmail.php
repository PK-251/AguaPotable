<?php

namespace App\Mail;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComprobanteEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Pago $pago,
    ) {
    }

    public function build(): self
    {
        return $this->subject('Comprobante de pago - J.A.S.S. Quilcata')
            ->view('mail.comprobante');
    }
}
