<?php

namespace App\Http\Controllers\Singer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Singer\SingerRequest;
use App\Repositories\SingerRepository;
use Illuminate\Http\Response;

class SingerStoreController extends Controller
{
    public function __construct(protected readonly SingerRepository $singers)
    {
    }

    public function __invoke(SingerRequest $request)
    {
        $loggedUserId = auth()->id();

        $created = $this->singers->create($loggedUserId, $request->name);

        if (!$created) {
            return $this->responseJson(
                message: trans('response-errors.duplicate_name'),
                status: Response::HTTP_CONFLICT
            );
        }

        return $this->responseJson(status: Response::HTTP_CREATED);
    }
}
