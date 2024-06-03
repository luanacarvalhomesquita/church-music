<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegisterRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse as Response;

class UserRegisterController extends Controller
{
    public function __construct(
        protected readonly UserRepository $users
    ) {
    }

    public function __invoke(UserRegisterRequest $request): Response
    {
        $this->users->register($request->all());

        return $this->responseJson(status: Response::HTTP_CREATED);
    }
}
