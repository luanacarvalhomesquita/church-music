<?php

namespace Tests\Feature\Singer;

use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\BindSingerMusicList;
use Tests\Feature\Helper\CreateSingerList;
use Tests\TestCase;

class SingerIndexFTest extends TestCase
{
    use CreateSingerList;
    use BindSingerMusicList;

    public function testSingerIndexSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singers = $this->createSingersFromUserFactories(
            singersName: [
                'Joao',
                'Ana',
                'Bruno'
            ],
            userId: $user->id
        );

        $params = [
            'page' => 1,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/singer?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseSingers = $returnedData['singers'];

        $response->assertStatus(200);

        $this->assertEquals($singers[1]['name'], $responseSingers[0]['name']);
        $this->assertEquals($singers[1]['id'], $responseSingers[0]['id']);

        $this->assertEquals($singers[2]['name'], $responseSingers[1]['name']);
        $this->assertEquals($singers[2]['id'], $responseSingers[1]['id']);

        $this->assertEquals($returnedData['total'], 3);
    }

    public function testSingerIndexEmpty(): void
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
            'api/singer?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseSingers = $returnedData['singers'];

        $response->assertStatus(200);
        $this->assertEmpty($responseSingers);
        $this->assertEquals($returnedData['total'], 0);
    }
}
