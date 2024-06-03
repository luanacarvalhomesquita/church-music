<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Tests\Feature\Helper\RepertoireList;

class RepertoiresSeeder extends Seeder
{
    use RepertoireList;

    public function run(): void
    {
        $this->createRepertoire(userId: 2);
    }
}
