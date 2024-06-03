<?php

namespace Database\Factories;

use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type>
 */
class TypeFactory extends Factory
{
    protected $model = Type::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'name' => $this->faker->name,
            'user_id' => $user->id,
        ];
    }
}
