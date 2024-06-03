<?php

namespace Tests\Feature\Singer;

use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\CreateSingerList;
use Tests\TestCase;

class SingerStoreFTest extends TestCase
{
     use CreateSingerList;

    public function testSingerStoreEqualsNamesError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singerName = 'Joaquim';
        $this->createSingersFromUserFactories(
            [$singerName],
            $user->id
        );

        $payload = ['name' => $singerName];
        $response = $this->actingAs($user)->post('api/singer', $payload);

        $message = $response->json()['message'];

        $response->assertStatus(409);
        $this->assertEquals('This name already exists.', $message);
    }

    public function testSingerStoreNewNameSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singerName = 'Mariana';

        $payload = ['name' => $singerName];

        $response = $this->actingAs($user)->post('api/singer', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('singers', [
            'user_id' => $user->id,
            'name' => $singerName,
        ]);
    }

    public function testSingerStoreOutherUserSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singerName = 'Maria';
        $this->createSingersFromUserFactories(
            [$singerName]
        );

        $payload = ['name' => $singerName];
        $response = $this->actingAs($user)->post('api/singer', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('singers', [
            'user_id' => $user->id,
            'name' => $singerName,
        ]);
    }
}
