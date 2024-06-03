<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserForgotPasswordRequest;
use App\Repositories\UserForgotPassword;
use Illuminate\Http\Response;

class ForgotPasswordController extends Controller
{
    public function __construct(
        protected readonly UserForgotPassword $userForgotPassword
    ) {
    }

    public function __invoke(UserForgotPasswordRequest $request)
    {
        $email = $request->all()['email'];

        $this->userForgotPassword->sendPinToEmail($email);

        return $this->responseJson(status: Response::HTTP_NO_CONTENT);
    }
}
