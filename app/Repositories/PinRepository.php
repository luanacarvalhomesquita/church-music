<?php

namespace App\Repositories;

use App\Models\ResetsPassword;
use DateTime;

class PinRepository
{
    public function __construct(
        protected readonly ResetsPassword $resetsPassword
    ) {
    }

    public function check(string $email, string $pin): bool
    {
        $passwordReset = $this->resetsPassword->where('pin', $pin)->where('email', $email)->first();

        if (!$passwordReset) {
            return false;
        }

        if ($passwordReset->expired_date < now()) {
            return false;
        }

        return true;
    }

    public function create(string $email): string
    {
        $this->clean($email);

        $pin = mt_rand(1000, 9999);
        $tomorrow = (new DateTime('now +1 day'))->format('Y-m-d H:i:s');

        $this->resetsPassword->create([
            'pin' => $pin,
            'email' => $email,
            'expired_date' => $tomorrow,
        ]);

        return $pin;
    }

    public function clean(string $email): void
    {
        $this->resetsPassword->where('email', $email)->delete();
    }
}
