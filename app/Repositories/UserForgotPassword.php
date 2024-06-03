<?php

namespace App\Repositories;

use App\Repositories\UserRepository;

class UserForgotPassword
{
    public function __construct(
        protected readonly UserRepository $users,
        protected readonly PinRepository $pin
    ) {
    }

    public function sendPinToEmail(string $email): void
    {
        $userName = $this->users->getNameByEmail($email);

        if (UserRepository::NO_NAME !== $userName) {
            $pin = $this->pin->create($email);

            // Mail::to($email)->send(
            //     new SendCodeResetPassword(
            //         $pin,
            //         $userName
            //     )
            // );
        }
    }
}
