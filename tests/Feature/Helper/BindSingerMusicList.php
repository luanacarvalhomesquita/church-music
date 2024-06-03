<?php

namespace Tests\Feature\Helper;

use App\Models\Music;
use App\Models\Singer;
use App\Models\SingerMusic;
use App\Models\User;

trait BindSingerMusicList
{
    private function bindSingersAndMusicsFromUserFactories(
        array $singerIds = [],
        array $musicIds = [],
        int $userId = null
    ): array {
        $singersMusics = [];

        if (!$userId) {
            $user = User::factory()->create();
            $userId = $user->id;
        }

        if (!$singerIds) {
            $singer = Singer::factory()->create([
                'user_id' => $userId,
            ]);
            $singerIds = [$singer['id']];
        }

        if (!$musicIds) {
            $music = Music::factory()->create([
                'user_id' => $userId,
            ]);
            $musicIds = [$music['id']];
        }

        foreach ($singerIds as $singerId) {
            foreach ($musicIds as $musicId) {
                $singersMusics[] = SingerMusic::factory()->create([
                    'singer_id' => $singerId,
                    'music_id' => $musicId,
                    'user_id' => $userId,
                ]);
            }
        }

        return $singersMusics;
    }
}
