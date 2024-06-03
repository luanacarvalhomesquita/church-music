<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Repositories\MusicRepository;
use Illuminate\Http\Response;

class MusicShowController extends Controller
{
    public function __construct(
        protected readonly MusicRepository $musics
    ) {
    }

    public function __invoke(int $musicId)
    {
        $loggedUserId = auth()->id();

        $music = $this->musics->getById(
            $musicId,
            $loggedUserId
        );

        if (!$music) {
            return $this->responseJson(
                message: trans('response-errors.not_found_music'),
                status: Response::HTTP_NOT_FOUND
            );
        }

        return $this->responseJson(
            data: $music,
            status: Response::HTTP_OK
        );
    }
}
