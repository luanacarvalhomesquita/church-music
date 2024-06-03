<?php

namespace Tests\Feature\Helper;

use App\Models\Type;
use App\Models\User;

trait CreateTypeList
{
    private function createTypesFromUserFactories(
        ?array $typesName,
        ?int $userId = null
    ): array {
        $types = [];

        if (!$typesName) {
            $typesName = ['Gratidão', 'Adoração'];
        }

        if(!$userId) {
            $user = User::factory()->create();
            $userId = $user->id;
        }

        foreach ($typesName as $name) {
            $types[] = Type::factory()->create([
                'name' => $name,
                'user_id' => $userId,
            ]);
        }
      
        return $types;
    }
}
