<?php

namespace Tests\Feature\Music;

use App\Models\Music;
use App\Models\Type;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\CreateMusicList;
use Tests\TestCase;

class MusicIndexFTest extends TestCase
{
    use CreateMusicList;

    public function testMusicIndexWithTypeSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type =  Type::factory()->create([
            'name' => 'Adoração',
            'user_id' => $user->id,
        ]);

        $musics = $this->createMusicsFromUserFactories(
            musicsName: [
                'Aleluia',
                'Celebrai',
                'Me Ama',
            ],
            userId: $user->id,
            typeId: $type->id
        );

        $params = [
            'page' => 1,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/music?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseMusics = $returnedData['musics'];

        $response->assertStatus(200);

        $this->assertEquals($musics[0]['name'], $responseMusics[0]['music_name']);
        $this->assertEquals($musics[0]['id'], $responseMusics[0]['music_id']);
        $this->assertEquals($musics[0]['description'], $responseMusics[0]['description']);
        $this->assertEquals($type['name'], $responseMusics[0]['type_name']);

        $this->assertEquals($musics[1]['name'], $responseMusics[1]['music_name']);
        $this->assertEquals($musics[1]['id'], $responseMusics[1]['music_id']);
        $this->assertEquals($musics[1]['description'], $responseMusics[1]['description']);
        $this->assertEquals($type['name'], $responseMusics[1]['type_name']);

        $this->assertEquals($returnedData['total'], 3);
    }

    public function testMusicIndexWithoutTypeSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $music = Music::factory()->create([
            'user_id' => $user->id,
            'type_id' => null,
        ]);

        $params = [
            'page' => 1,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/music?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseMusics = $returnedData['musics'];

        $response->assertStatus(200);

        $this->assertEquals($music['id'], $responseMusics[0]['music_id']);
        $this->assertEquals($music['name'], $responseMusics[0]['music_name']);
        $this->assertEquals($music['main_version'], $responseMusics[0]['main_version']);
        $this->assertEquals($music['description'], $responseMusics[0]['description']);
        $this->assertEquals($music['played'], $responseMusics[0]['played']);
        $this->assertEmpty($responseMusics[0]['type_name']);

        $this->assertEquals($returnedData['total'], 1);
    }

    public function testMusicIndexEmpty(): void
    {
        $this->createMusicsFromUserFactories();

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $params = [
            'page' => 1,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/music?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseMusics = $returnedData['musics'];

        $response->assertStatus(200);

        $this->assertEmpty($responseMusics);

        $this->assertEquals($returnedData['total'], 0);
    }
}
