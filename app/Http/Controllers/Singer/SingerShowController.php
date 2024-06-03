<?php

namespace App\Http\Controllers\Singer;

use App\Http\Controllers\Controller;
use App\Repositories\SingerRepository;
use Illuminate\Http\Response;

class SingerShowController extends Controller
{
    public function __construct(protected readonly SingerRepository $singers)
    {
    }

    public function __invoke(int $singerId)
    {
        $loggedUserId = auth()->id();

        $singer = $this->singers->getById($singerId, $loggedUserId);

        if (!$singer) {
            return $this->responseJson(
                message: trans('response-errors.not_found_singer'),
                status: Response::HTTP_NOT_FOUND
            );
        }

        return $this->responseJson(
            data: $singer,
            status: Response::HTTP_OK
        );
    }
}
