<?php

namespace App\Http\Controllers;

use App\Http\Requests\Repertoire\RepertoireSingerRequest;
use App\Models\SingerRepertoire;
use App\Models\Repertoire;
use Illuminate\Http\Response;

class SingerRepertoireController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param int $repertoireId
     * @param RepertoireSingerRequest $request
     * @return Response
     */
    public function unbind(int $repertoireId, RepertoireSingerRequest $request)
    {
        $loggedUserId = auth()->id();

        $repertoire = Repertoire::where('user_id', $loggedUserId)->where('id', $repertoireId)->first();
        if (!$repertoire) {
            return response()->json([
                'message' => 'Ther repertoire not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        SingerRepertoire::where('repertoire_id', $repertoireId)->delete();

        return $this->bind($repertoireId, $request);
    }
}
