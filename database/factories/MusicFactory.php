<?php

namespace Database\Factories;

use App\Models\Music;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Music>
 */
class MusicFactory extends Factory
{
    protected $model = Music::class;

    public function definition(): array
    {
        $user = User::factory()->create();
        $type = Type::factory()->create([
            'user_id' => $user->id,
        ]);

        return [
            'user_id' => $user->id,
            'type_id' => $type->id,
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'main_version' => $this->faker->name,
            'played' => true,
        ];
    }
}
