<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\MusicRequest;
use App\Repositories\MusicRepository;
use App\Repositories\TypeRepository;
use Illuminate\Http\Response;

class MusicUpdateController extends Controller
{
    public function __construct(
        protected readonly MusicRepository $musics,
        protected readonly TypeRepository $types
    ) {
    }

    public function __invoke(MusicRequest $request, int $musicId)
    {
        $loggedUserId = auth()->id();
        $fields = $request->all();

        $validType = $this->types->checkType(
            $loggedUserId,
            $fields['type_id']
        );

        if (!$validType) {
            return $this->responseJson(
                message: trans('response-errors.not_found_type'),
                status: Response::HTTP_NOT_FOUND
            );
        }

        $validMusicName = $this->musics->checkUniqueMusicName(
            $loggedUserId,
            $fields['name'],
            $musicId
        );

        if (!$validMusicName) {
            return $this->responseJson(
                message: trans('response-errors.duplicate_name'),
                status: Response::HTTP_CONFLICT
            );
        }

        $this->musics->update(
            $loggedUserId,
            $musicId,
            $fields['name'],
            $fields['description'],
            $fields['main_version'],
            $fields['played'],
            $fields['type_id']
        );

        return $this->responseJson();
    }
}
