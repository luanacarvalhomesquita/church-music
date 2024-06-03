<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserPinCheckRequest;
use App\Repositories\PinRepository;
use Illuminate\Http\Response;

class PinCheckController extends Controller
{
    public function __construct(
        protected readonly PinRepository $pin
    ) {
    }

    public function __invoke(UserPinCheckRequest $request)
    {
        $isValid = $this->pin->check($request->email, $request->pin);

        if (!$isValid) {
            $this->pin->clean($request->email);

            return $this->responseJson(
                message: trans('response-errors.invalid_pin'),
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->responseJson(status: Response::HTTP_NO_CONTENT);
    }
}
