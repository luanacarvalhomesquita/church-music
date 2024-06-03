<?php

namespace App\Repositories;

use App\Models\User;

class UserResetPassword
{
    public function __construct(
        protected readonly User $users,
        protected readonly PinRepository $pin
    ) {
    }

    public function resetPassword(string $email, string $pin, string $newPassword): bool
    {
        $validPin = $this->pin->check($email, $pin);

        if (!$validPin) {
            $this->pin->clean($email);

            return false;
        }

        $this->users
            ->where('email', $email)
            ->update(['password' => bcrypt($newPassword)], [
                'email', $email,
            ]);

        $this->pin->clean($email);

        return true;
    }
}
