<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse as Response;

class UserLoginController extends Controller
{
    public function __construct(
        protected readonly UserRepository $users
    ) {
    }

    public function __invoke(UserLoginRequest $request): Response
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
}
