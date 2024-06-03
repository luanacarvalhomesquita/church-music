<?php

namespace App\Http\Controllers;

use App\Http\Requests\Repertoire\RepertoireMusicRequest;
use App\Http\Requests\Repertoire\RepertoirePaginationRequest;
use App\Models\Music;
use App\Models\Repertoire;
use Illuminate\Http\Response;

class MusicRepertoireController extends Controller
{
    public function getMusicsByRepertoire(RepertoirePaginationRequest $request, int $repertoireId)
    {
        $loggedUserId = auth()->id();

        $repertoireInfo = Repertoire::where('user_id', $loggedUserId)
            ->where('id', $repertoireId)
            ->first(['id', 'title', 'date', 'description']);

        if (!$repertoireInfo) {
            return response()->json([
                'message' => 'This repertoire not exists.',
            ], Response::HTTP_CONFLICT);
        }

        $musics = Music::where('user_id', $loggedUserId)->orderBy('name', 'desc');

        $total = count($musics->get());
        $musicsInfo = $musics->forPage($request->page, $request->size)->get([
            'id',
            'name',
            'description',
            'main_version',
            'played',
            'type_id',
        ]);

        return [
            'repertoire' => $repertoireInfo,
            'musics' => [
                'results' => $musicsInfo,
                'page' => $request->page,
                'size' => $request->size,
                'total' => $total,
            ],
        ];
    }

    public function unbind(int $repertoireId, RepertoireMusicRequest $request)
    {
        $loggedUserId = auth()->id();

        $repertoire = Repertoire::where('user_id', $loggedUserId)->where('id', $repertoireId)->first();
        if (!$repertoire) {
            return response()->json([
                'message' => 'Ther repertoire not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        HistoryRepertoireMusic::where('repertoire_id', $repertoireId)->delete();

        return $this->bind($repertoireId, $request);
    }
}
