<?php

namespace Database\Seeders;

use App\Models\Music;
use Illuminate\Database\Seeder;

class MusicsSeeder extends Seeder
{
    public function run(): void
    {
        Music::factory(20)->create(['user_id' => 2]);
    }
}
