<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserForgotPasswordRequest;
use App\Repositories\UserForgotPassword;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function __construct(
        protected readonly UserForgotPassword $userForgotPassword
    ) {
    }

     /**
     * Inform the credentials to enter the account.
     *
     * @param UserLoginRequest  $request
     * @return Response
     */
    public function login(UserLoginRequest $request): Response
    {
        $fields = $request->all();

        $token = $this->users->login($fields['email'], $fields['password']);

        if (!$token) {
            return $this->responseJson(
                message: trans('response-errors.incorrect_authentication'),
                status: Response::HTTP_UNAUTHORIZED
            );
        }
        
        return $this->responseJson(
            data: ['token' => $token],
            status: Response::HTTP_OK
        );
    }


    public function __invoke(UserForgotPasswordRequest $request)
    {
        $email = $request->all()['email'];

        $this->userForgotPassword->sendPinToEmail($email);

        return $this->responseJson(status: Response::HTTP_NO_CONTENT);
    }
}
