<?php

namespace App\Mail;

use App\Models\PadronUsuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AvisoDeudaMailable extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public PadronUsuario $padronUsuario,
    ) {
    }

    public function build(): self
    {
        return $this->subject('Aviso de deuda - J.A.S.S. Quilcata')
            ->view('mail.aviso-deuda');
    }
}
