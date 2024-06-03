<?php

namespace Tests\Feature\Helper;

use App\Models\Singer;
use App\Models\User;

trait CreateSingerList
{
    private function createSingersFromUserFactories(
        ?array $singersName = [],
        ?int $userId = null
    ): array {
        $singers = [];

        if (!$singersName) {
            $singersName = ['João', 'Maria'];
        }

        if(!$userId) {
            $user = User::factory()->create();
            $userId = $user->id;
        }

        foreach ($singersName as $name) {
            $singers[] = Singer::factory()->create([
                'name' => $name,
                'user_id' => $userId,
            ]);
        }
      
        return $singers;
    }
}
