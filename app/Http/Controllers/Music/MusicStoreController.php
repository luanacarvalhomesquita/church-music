<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\MusicRequest;
use App\Repositories\MusicRepository;
use App\Repositories\TypeRepository;
use App\Repositories\SingerMusicRepository;
use Illuminate\Http\Response;

class MusicStoreController extends Controller
{
    public function __construct(
        protected readonly MusicRepository $musics,
        protected readonly TypeRepository $types,
        protected readonly SingerMusicRepository $singerMusics
    ) {
    }

    public function __invoke(MusicRequest $request)
    {
        $loggedUserId = auth()->id();
        $fields = $request->all();

        $validType = $this->types->checkType(
            $loggedUserId,
            $fields['type_id'] ?? null
        );

        if (!$validType) {
            return $this->responseJson(
                message: trans('response-errors.not_found_type'),
                status: Response::HTTP_NOT_FOUND
            );
        }

        $musicId = $this->musics->create(
            $loggedUserId,
            $fields['name'],
            $fields['description'],
            $fields['main_version'],
            $fields['played'],
            $fields['type_id'] ?? null,
        );

        if (!$musicId) {
            return $this->responseJson(
                message: trans('response-errors.duplicate_name'),
                status: Response::HTTP_CONFLICT
            );
        }

        return $this->responseJson(status: Response::HTTP_CREATED);
    }
}
