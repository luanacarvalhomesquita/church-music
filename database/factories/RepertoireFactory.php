<?php

namespace Database\Factories;

use App\Models\Repertoire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repertoire>
 */
class RepertoireFactory extends Factory
{
    protected $model = Repertoire::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'title' => $this->faker->title(),
            'date' => $this->faker->date('Y-m-d'),
        ];
    }
}
