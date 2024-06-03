<?php

namespace App\Http\Controllers\Singer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Singer\SingerPaginationRequest;
use App\Repositories\SingerRepository;
use Illuminate\Http\Response;

class SingerIndexController extends Controller
{
    public function __construct(protected readonly SingerRepository $singers)
    {
    }

    public function __invoke(SingerPaginationRequest $request)
    {
        $loggedUserId = auth()->id();
        $pagination = $request->all();

        $singers = $this->singers->getAllForPage(
            $loggedUserId,
            $pagination['page'],
            $pagination['size']
        );

        return $this->responseJson(
            status: Response::HTTP_OK,
            data: $singers
        );
    }
}
