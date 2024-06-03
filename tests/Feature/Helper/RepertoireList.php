<?php

namespace Tests\Feature\Helper;

use App\Models\MusicRepertoire;
use App\Models\SingerRepertoire;
use App\Models\Music;
use App\Models\Repertoire;
use App\Models\Singer;
use App\Models\SingerMusic;
use App\Models\User;

trait RepertoireList
{
    private function createRepertoire(
        array $singerIds = [],
        array $musicIds = [],
        int $repertoireId = null,
        int $userId = null
    ): array {
        $singersMusics = [];
        $music = [];
        $singer = [];

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

        if (!$repertoireId) {
            $repertoire = Repertoire::factory()->create([
                'user_id' => $userId,
            ]);

            $repertoireId = $repertoire->id;
        }

        foreach ($singerIds as $singerId) {
            SingerRepertoire::factory()->create([
                'repertoire_id' => $repertoireId,
                'singer_id' => $singerId,
            ]);
        }

        foreach ($musicIds as $musicId) {
            MusicRepertoire::factory()->create([
                'repertoire_id' => $repertoireId,
                'music_id' => $musicId,
            ]);
        }

        return [
            'repertoire' => isset($repertoire) ? $repertoire : [],
            'musics' => [$music],
            'singers' => [$singer],
        ];
    }
}
