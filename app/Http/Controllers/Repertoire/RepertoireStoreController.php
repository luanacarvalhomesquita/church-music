<?php

namespace App\Http\Controllers\Repertoire;

use App\Http\Controllers\Controller;
use App\Http\Requests\Repertoire\RepertoireRequest;
use App\Repositories\MusicRepertoireRepository;
use App\Repositories\RepertoireRepository;
use App\Repositories\SingerRepertoireRepository;
use Illuminate\Http\Response;

class RepertoireStoreController extends Controller
{
    /**
     * @param RepertoireRepository $repertoires
     * @param MusicRepertoireRepository $musicRepertoire
     * @return void
     */
    public function __construct(
        protected readonly RepertoireRepository $repertoires,
        protected readonly MusicRepertoireRepository $musicRepertoire,
        protected readonly SingerRepertoireRepository $singerRepertoire
    ) {
    }

    /**
     * @param RepertoireRequest $request
     * @return Response
     */
    public function __invoke(RepertoireRequest $request)
    {
        $loggedUserId = auth()->id();
        $fields = $request->all();

        $repertoireId = $this->repertoires->create(
            $loggedUserId,
            $fields['title'],
            $fields['date']
        );

        if (!$repertoireId) {
            return $this->responseJson(
                message: 'This title with this date already exists.',
                status: Response::HTTP_CONFLICT
            );
        }

        $savedBindMusics = $this->musicRepertoire->bindMusics(
            $fields['musics'] ?? [],
            $repertoireId
        );

        if (!$savedBindMusics) {
            return $this->responseJson(
                message: 'This music not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        $savedBindSingers = $this->singerRepertoire->bindSingers(
            $fields['singers'] ?? [],
            $repertoireId
        );

        if (!$savedBindSingers) {
            return $this->responseJson(
                message: 'Ther singer not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        return $this->responseJson();
    }
}
