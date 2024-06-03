<?php

namespace Tests\Feature\Singer;

use App\Models\Music;
use App\Models\Singer;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\BindSingerMusicList;
use Tests\TestCase;

class SingerShowFTest extends TestCase
{
     use BindSingerMusicList;

    public function testSingerShowWithoutMusicSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singer = Singer::factory()->create([
            'name' => 'Maria',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("api/singer/{$singer->id}", []);

        $response->assertStatus(200);
        
        $returnedData = $response->json()['data'];
        $expectedSinger = $returnedData['singer'];
        $expectedMusics = $returnedData['musics'];

        $this->assertEquals($singer['name'], $expectedSinger['name']);
        $this->assertEquals($singer['id'], $expectedSinger['id']);

        $this->assertEmpty($expectedMusics);
    }

    public function testSingerShowOutherUserNotFoundError(): void
    {
        $singer = Singer::factory()->create(['name' => 'Maria']);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->get("api/singer/{$singer->id}", []);

        $message = $response->json()['message'];

        $response->assertStatus(404);
        $this->assertEquals('Ther singer not found.', $message);
    }

    public function testSingerShowWithMusicsSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singer =  Singer::factory()->create([
            'user_id' => $user->id,
        ]);

        $music1 =  Music::factory()->create([
            'name' => 'Me Ama',
            'user_id' => $user->id,
        ]);
        $music2 =  Music::factory()->create([
            'name' => 'Aleluia',
            'user_id' => $user->id,
        ]);

        $this->bindSingersAndMusicsFromUserFactories(
            singerIds: [$singer['id']],
            musicIds: [$music1['id'], $music2['id']],
            userId: $user->id
        );

        $response = $this->actingAs($user)->get("api/singer/{$singer->id}");

        $response->assertStatus(200);
        $jsonBody = $response->json()['data'];
        $responseSinger = $jsonBody['singer'];
        $responseMusics = $jsonBody['musics'];

        $this->assertEquals($singer['id'], $responseSinger['id']);
        $this->assertEquals($singer['name'], $responseSinger['name']);

        $this->assertEquals($music1['id'], $responseMusics[1]['id']);
        $this->assertEquals($music1['name'], $responseMusics[1]['name']);
        $this->assertEquals($music1['description'], $responseMusics[1]['description']);
        $this->assertEquals($music1['main_version'], $responseMusics[1]['main_version']);
        $this->assertEquals($music1['played'], $responseMusics[1]['played']);

        $this->assertEquals($music2['id'], $responseMusics[0]['id']);
        $this->assertEquals($music2['name'], $responseMusics[0]['name']);
        $this->assertEquals($music2['description'], $responseMusics[0]['description']);
        $this->assertEquals($music2['main_version'], $responseMusics[0]['main_version']);
        $this->assertEquals($music2['played'], $responseMusics[0]['played']);
    }
}
