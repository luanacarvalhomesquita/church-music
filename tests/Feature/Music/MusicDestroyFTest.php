<?php

namespace Tests\Feature\Music;

use App\Models\Music;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\Feature\Helper\BindSingerMusicList;
use Tests\TestCase;

class MusicDestroyFTest extends TestCase
{
    use BindSingerMusicList;

    public function testMusicDestroyWithoutSingersSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $music = Music::factory()->create([
            'name' => 'Me Ama',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete("api/music/{$music->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('singers', [
            'id' => $music['id'],
            'name' => $music['name'],
        ]);
    }

    public function testMusicDestroyWithSingersSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $music = Music::factory()->create([
            'name' => 'Me Ama',
            'user_id' => $user->id
        ]);

        $musicsSingers = $this->bindSingersAndMusicsFromUserFactories(
            userId: $user->id,
            musicIds: [$music['id']]
        );

        $response = $this->actingAs($user)->delete("api/music/{$music->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('singers', [
            'id' => $music['id'],
            'name' => $music['name'],
        ]);

        $this->assertDatabaseMissing('singer_music', [
            'id' => $musicsSingers[0]['id'],
        ]);
    }

    public function testMusicDestroyOutherUserError(): void
    {
        $music = Music::factory()->create([
            'name' => 'Me Ama',
        ]);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->delete("api/music/{$music->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('music', [
            'id' => $music['id'],
            'name' => $music['name'],
        ]);
    }
}
