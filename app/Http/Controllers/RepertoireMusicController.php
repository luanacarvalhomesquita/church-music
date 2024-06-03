<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Repertoire\RepertoirePaginationRequest;
use App\Models\Music;
use App\Models\Repertoire;
use App\Repositories\RepertoireRepository;
use Illuminate\Http\Response;

class RepertoireMusicController extends Controller
{
    public function __construct(protected readonly RepertoireRepository $repertoires)
    {
    }

    public function getRepertoiresByMusic(RepertoirePaginationRequest $request, int $musicId): array
    {
        $loggedUserId = auth()->id();

        $musicInfo = Music::where('user_id', $loggedUserId)
            ->where('id', $musicId)
            ->first(['id', 'name', 'played', 'main_version', 'type_id']);

        if (!$musicInfo) {
            return response()->json([
                'message' => 'This music not exists.',
            ], Response::HTTP_CONFLICT);
        }

        $repertoires = Repertoire::where('user_id', $loggedUserId)->orderBy('date', 'desc');

        $total = count($repertoires->get());
        $repertoiresInfo = $repertoires->forPage($request->page, $request->size)->get([
            'id',
            'title',
            'date',
        ]);

        return [
            'music' => $musicInfo,
            'repertoires' => [
                'results' => $repertoiresInfo,
                'page' => $request->page,
                'size' => $request->size,
                'total' => $total,
            ],
        ];
    }
}
