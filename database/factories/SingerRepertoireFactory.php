<?php

namespace Database\Factories;

use App\Models\SingerRepertoire;
use App\Models\Repertoire;
use App\Models\Singer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SingerRepertoire>
 */
class SingerRepertoireFactory extends Factory
{
    protected $model = SingerRepertoire::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $repertoire = Repertoire::factory()->create();
        $singer = Singer::factory()->create();

        return [
            'repertoire_id' => $repertoire->id,
            'singer_id' => $singer->id,
            'singer_name' => $singer['name'],
        ];
    }
}
