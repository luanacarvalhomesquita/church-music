<?php

namespace Database\Factories;

use App\Models\MusicRepertoire;
use App\Models\Music;
use App\Models\Repertoire;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MusicRepertoire>
 */
class MusicRepertoireFactory extends Factory
{
    protected $model = MusicRepertoire::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $repertoire = Repertoire::factory()->create();
        $music = Music::factory()->create();

        return [
            'repertoire_id' => $repertoire->id,
            'music_id' => $music->id,
            'music_name' => $music['name'],
            'tone' => array_rand(Music::TONES),
        ];
    }
}
