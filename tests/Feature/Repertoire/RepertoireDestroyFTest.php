<?php

namespace Tests\Feature\Repertoire;

use App\Models\Repertoire;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\Feature\Helper\RepertoireList;
use Tests\TestCase;

class RepertoireDestroyFTest extends TestCase
{
    use RepertoireList;

    public function testRepertoireDestroyWithoutMusicAndSingerSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $repertoire = Repertoire::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete("api/repertoire/{$repertoire->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('repertoires', [
            'id' => $repertoire['id'],
            'user_id' => $user->id,
        ]);
    }

    public function testRepertoireDestroyWithMusicAndSingerSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $repertoireLinks = $this->createRepertoire(userId: $user->id);

        $repertoireId = $repertoireLinks['repertoire']->id;

        $response = $this->actingAs($user)->delete("api/repertoire/{$repertoireId}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('repertoires', [
            'id' => $repertoireId,
        ]);

        $this->assertDatabaseHas('music_repertoire', [
            'repertoire_id' => $repertoireId,
            'music_id' => $repertoireLinks['musics'][0]['id'],
        ]);
        $this->assertDatabaseHas('singer_repertoire', [
            'repertoire_id' => $repertoireId,
            'singer_id' => $repertoireLinks['singers'][0]['id'],
        ]);
    }

    public function testRepertoireDestroyOutherUserError(): void
    {
        $repertoire = Repertoire::factory()->create();

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->delete("api/repertoire/{$repertoire->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('repertoires', [
            'id' => $repertoire['id'],
        ]);
    }
}
