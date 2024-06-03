<?php

namespace Database\Seeders;

use App\Models\Singer;
use Illuminate\Database\Seeder;

class SingersSeeder extends Seeder
{
    public function run(): void
    {
        Singer::factory(20)->create(['user_id' => 2]);
    }
}
