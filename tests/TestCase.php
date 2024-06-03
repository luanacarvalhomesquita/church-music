<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    
    /**
     * Sign in the given user or create new one if not provided.
     * 
     * @param $user \App\User
     * 
     * @return \App\User
     */
    protected function signIn($user = null)
    {
        $user = $user ?:  Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        return $user;
    }
}
