<?php

namespace Tests\Feature\Music;

use App\Models\Music;
use App\Models\Type;
use App\Models\Singer;
use App\Models\SingerMusic;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

class MusicShowFTest extends TestCase
{
 
    public function testMusicShowSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Celebração',
            'user_id' => $user->id,
        ]);

        $music = Music::factory()->create([
            'name' => 'Me Ama',
            'user_id' => $user->id,
            'type_id' =>  $type->id,
        ]);

        $singer = Singer::factory()->create([
            'user_id' => $user->id,
        ]);

        SingerMusic::factory()->create([
            'singer_id' => $singer->id,
            'music_id' => $music->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("api/music/{$music->id}");

        $response->assertStatus(200);
        
        $returnedMusic = $response->json()['data']['music'];

        $this->assertEquals($music['id'], $returnedMusic['music_id']);
        $this->assertEquals($music['name'], $returnedMusic['music_name']);
        $this->assertEquals($music['description'], $returnedMusic['description']);
        $this->assertEquals($music['main_version'], $returnedMusic['main_version']);
        $this->assertEquals($music['played'], $returnedMusic['played']);
        $this->assertEquals($type['name'], $returnedMusic['type_name']);
        $this->assertEquals($type['id'], $returnedMusic['type_id']);
    }

    public function testMusicShowOutherUserNotFoundError(): void
    {
        $music = Music::factory()->create(['name' => 'Maria']);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->get("api/music/{$music->id}", []);

        $message = $response->json()['message'];

        $response->assertStatus(404);
        $this->assertEquals('This music not found.', $message);
    }

    public function testMusicShowWithoutSingersAndTypeSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $music = Music::factory()->create([
            'name' => 'Me Ama',
            'user_id' => $user->id,
            'type_id' =>  null,
        ]);

        $response = $this->actingAs($user)->get("api/music/{$music->id}");

        $response->assertStatus(200);

        $returnedMusic = $response->json()['data']['music'];

        $this->assertEquals($music['id'], $returnedMusic['music_id']);
        $this->assertEquals($music['name'], $returnedMusic['music_name']);
        $this->assertEquals($music['description'], $returnedMusic['description']);
        $this->assertEquals($music['main_version'], $returnedMusic['main_version']);
        $this->assertEquals($music['played'], $returnedMusic['played']);
        $this->assertEquals($music['type_id'], $returnedMusic['type_id']);
        $this->assertEmpty($returnedMusic['type_name']);
        
    }
}
