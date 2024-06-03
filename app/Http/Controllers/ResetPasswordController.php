<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserResetPasswordRequest;
use App\Repositories\UserResetPassword;
use Illuminate\Http\Response;

class ResetPasswordController extends Controller
{
    public function __construct(
        protected readonly UserResetPassword $userResetPassword
    ) {
    }

    public function __invoke(UserResetPasswordRequest $request)
    {
        $fields = $request->all();

        $isUpdated = $this->userResetPassword->resetPassword(
            $fields['email'],
            $fields['pin'],
            $fields['password']
        );

        if (!$isUpdated) {
            return $this->responseJson(
                message: trans('response-errors.invalid_pin'),
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->responseJson(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
