<?php

namespace Tests\Feature\Type;

use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\CreateTypeList;
use Tests\TestCase;

class TypeStoreFTest extends TestCase
{
     use CreateTypeList;

    public function testTypeStoreEqualsNamesError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $typeName = 'Celebração';
        $this->createTypesFromUserFactories(
            [$typeName],
            $user->id
        );

        $payload = ['name' => $typeName];
        $response = $this->actingAs($user)->post('api/type', $payload);

        $response->assertStatus(409);
        
        $message = $response->json()['message'];
        $this->assertEquals('This name already exists.', $message);
    }

    public function testTypeStoreNewNameSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $typeName = 'Celebração';

        $payload = ['name' => $typeName];

        $response = $this->actingAs($user)->post('api/type', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('types', [
            'user_id' => $user->id,
            'name' => $typeName,
        ]);
    }

    public function testTypeStoreOutherUserSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $typeName = 'Celebração';
        $this->createTypesFromUserFactories(
            [$typeName]
        );

        $payload = ['name' => $typeName];
        $response = $this->actingAs($user)->post('api/type', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('types', [
            'user_id' => $user->id,
            'name' => $typeName,
        ]);
    }
}
