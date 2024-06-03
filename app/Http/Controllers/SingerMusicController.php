<?php

namespace App\Http\Controllers;

use App\Http\Requests\SingerMusic\SingerMusicRequest;
use App\Models\Music;
use App\Models\Singer;
use App\Models\SingerMusic;
use Illuminate\Http\Response;

class SingerMusicController extends Controller
{
    public function bind(SingerMusicRequest $request)
    {
        $loggedUserId = auth()->id();
        $singerId = $request->route('singerId');
        $musicId = $request->route('musicId');

        $singer = Singer::where('user_id', $loggedUserId)->where('id', $singerId)->first();
        if (!$singer) {
            return response()->json([
                'message' => 'Ther singer not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $music = Music::where('user_id', $loggedUserId)->where('id', $musicId)->first();
        if (!$music) {
            return response()->json([
                'message' => 'This music not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return SingerMusic::firstOrCreate([
            'singer_id' => $singerId,
            'music_id' => $musicId,
            'tone' => $request->tone,
            'version' => $request->version,
            'user_id' => $loggedUserId,
        ]);
    }

    public function unbind(int $singerMusicId)
    {
        $loggedUserId = auth()->id();

        $singer = SingerMusic::where('user_id', $loggedUserId)->where('id', $singerMusicId)->first();
        if (!$singer) {
            return response()->json([
                'message' => 'Ther singer music not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $affectedRow = $singer->delete();
        if (!$affectedRow) {
            return response()->json([
                'message' => 'Ther bind not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return new Response(['deleted' => (bool) $affectedRow], Response::HTTP_OK);
    }
}
