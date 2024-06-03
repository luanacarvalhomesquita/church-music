<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCodeResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly string $pin,
        private readonly string $nameUser
    ) {
    }

    public function build()
    {
        return $this->markdown('auth.reset-password')
            ->with('pin', $this->pin)
            ->with('nameUser', $this->nameUser);
    }
}
