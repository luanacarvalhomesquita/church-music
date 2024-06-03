<?php

namespace Database\Factories;

use App\Models\Music;
use App\Models\Singer;
use App\Models\SingerMusic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SingerMusic>
 */
class SingerMusicFactory extends Factory
{
    protected $model = SingerMusic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory()->create();
        $music = Music::factory()->create();
        $singer = Singer::factory()->create();

        return [
            'tone' => array_rand(Music::TONES),
            'version' => $this->faker->name(),
            'user_id' => $user->id,
            'music_id' => $music->id,
            'singer_id' => $singer->id,
        ];
    }
}
