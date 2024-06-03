<?php

namespace Database\Factories;

use App\Models\ResetsPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResetsPassword>
 */
class ResetsPasswordFactory extends Factory
{
    protected $model = ResetsPassword::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        $expiredDate = Carbon::now()->addMinutes(15);

        return [
            'email' => $user->email,
            'pin' => mt_rand(1000, 9999),
            'expired_date' => $expiredDate,
        ];
    }
}
