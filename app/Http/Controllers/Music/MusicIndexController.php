<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\MusicPaginationRequest;
use App\Repositories\MusicRepository;
use Illuminate\Http\Response;

class MusicIndexController extends Controller
{
    public function __construct(
        protected readonly MusicRepository $musics
    ) {
    }

    public function __invoke(MusicPaginationRequest $request)
    {
        $loggedUserId = auth()->id();

        $musics = $this->musics->getAll(
            $loggedUserId,
            $request->page,
            $request->size,
            $request->name,
            $request->type_id
        );

        return $this->responseJson(
            status: Response::HTTP_OK,
            data: $musics
        );
    }
}
