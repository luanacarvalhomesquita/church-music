<?php

namespace Tests\Feature\Helper;

use App\Models\Music;
use App\Models\Type;
use App\Models\User;

trait CreateMusicList
{
    private function createMusicsFromUserFactories(
        ?array $musicsName = null,
        ?int $userId = null,
        ?int $typeId = null,
    ): array {
        $musics = [];

        if (!$musicsName) {
            $musicsName = ['Aleluia', 'Deus Me Ama'];
        }

        if (!$userId) {
            $user = User::factory()->create();
            $userId = $user->id;
        }

        if (!$typeId) {
            $type = Type::factory()->create([
                'name' => 'Adoração',
                'user_id' => $userId,
            ]);
            $typeId = $type->id;
        }

        foreach ($musicsName as $name) {
            $musics[] = Music::factory()->create([
                'name' => $name,
                'user_id' => $userId,
                'type_id' => $typeId,
            ]);
        }

        return $musics;
    }
}
