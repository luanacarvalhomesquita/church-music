<?php

namespace Tests\Feature\Type;

use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\CreateTypeList;
use Tests\TestCase;

class TypeIndexFTest extends TestCase
{
    use CreateTypeList;

    public function testTypeIndexSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $types = $this->createTypesFromUserFactories(
            typesName: [
                'Celebração',
                'Adoracao',
                'Gratidão'
            ],
            userId: $user->id
        );

        $params = [
            'page' => 1,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/type?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseTypes = $returnedData['types'];

        $response->assertStatus(200);

        $this->assertEquals($types[1]['name'], $responseTypes[0]['name']);
        $this->assertEquals($types[1]['id'], $responseTypes[0]['id']);

        $this->assertEquals($types[0]['name'], $responseTypes[1]['name']);
        $this->assertEquals($types[0]['id'], $responseTypes[1]['id']);

        $this->assertEquals($returnedData['total'], 3);
    }

    public function testTypeIndexEmpty(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $params = [
            'page' => 2,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/type?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseTypes = $returnedData['types'];

        $response->assertStatus(200);

        $this->assertEmpty($responseTypes);

        $this->assertEquals($returnedData['total'], 0);
    }
}
