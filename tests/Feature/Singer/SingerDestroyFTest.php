<?php

namespace Tests\Feature\Singer;

use App\Models\Singer;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\Feature\Helper\BindSingerMusicList;
use Tests\TestCase;

class SingerDestroyFTest extends TestCase
{
    use BindSingerMusicList;

    public function testSingerDestroyWithoutMusicSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singer = Singer::factory()->create([
            'name' => 'Adoração',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete("api/singer/{$singer->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('singers', [
            'id' => $singer['id'],
            'name' => $singer['name'],
        ]);
    }

    public function testSingerDestroyWithMusicSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singer = Singer::factory()->create([
            'name' => 'Adoração',
            'user_id' => $user->id
        ]);

        $singersMusics = $this->bindSingersAndMusicsFromUserFactories(
            userId: $user->id,
            singerIds: [$singer['id']]
        );

        $response = $this->actingAs($user)->delete("api/singer/{$singer->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('singers', [
            'id' => $singer['id'],
            'name' => $singer['name'],
        ]);

        $this->assertDatabaseMissing('singer_music', [
            'id' => $singersMusics[0]['id'],
        ]);
    }

    public function testSingerDestroyOutherUserError(): void
    {
        $singer = Singer::factory()->create([
            'name' => 'Adoração',
        ]);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->delete("api/singer/{$singer->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('singers', [
            'id' => $singer['id'],
            'name' => $singer['name'],
        ]);
    }
}
