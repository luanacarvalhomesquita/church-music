<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Repositories\MusicRepository;

class MusicDestroyController extends Controller
{
    public function __construct(
        protected readonly MusicRepository $musics
    ) {
    }

    public function __invoke(int $musicId)
    {
        $loggedUserId = auth()->id();

        $this->musics->delete($musicId, $loggedUserId);

        return $this->responseJson();
    }
}
