<?php

namespace Tests\Feature\Repertoire;


use App\Models\MusicRepertoire;
use App\Models\SingerRepertoire;
use App\Models\Music;
use App\Models\Repertoire;
use App\Models\Singer;
use App\Models\SingerMusic;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\RepertoireList;
use Tests\TestCase;

class RepertoireShowFTest extends TestCase
{
    use RepertoireList;

    public function testRepertoireWithoutMusicsAndSingersSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $repertoire = Repertoire::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("api/repertoire/{$repertoire->id}", []);

        $response->assertStatus(200);

        $returnedData = $response->json()['data'];
        $repertoire = $returnedData['repertoire'];
        $musics = $returnedData['musics'];
        $singers = $returnedData['singers'];

        $this->assertEquals($repertoire['id'], $repertoire['id']);
        $this->assertEquals($repertoire['title'], $repertoire['title']);
        $this->assertEquals($repertoire['date'], $repertoire['date']);

        $this->assertEmpty($musics);
        $this->assertEmpty($singers);
    }

    public function testRepertoireWithMusicsAndSingersSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singer = Singer::factory()->create([
            'user_id' => $user->id,
        ]);
        $music = Music::factory()->create([
            'user_id' => $user->id,
        ]);
        SingerMusic::factory()->create([
            'singer_id' => $singer['id'],
            'music_id' => $music['id'],
            'user_id' => $user->id,
        ]);

        $repertoire = Repertoire::factory()->create([
            'user_id' => $user->id,
        ]);

        SingerRepertoire::factory()->create([
            'repertoire_id' => $repertoire['id'],
            'singer_id' => $singer['id'],
        ]);

        MusicRepertoire::factory()->create([
            'repertoire_id' => $repertoire['id'],
            'music_id' => $music['id'],
        ]);

        $response = $this->actingAs($user)->get("api/repertoire/{$repertoire['id']}", []);

        $response->assertStatus(200);

        $returnedData = $response->json()['data'];
        $repertoire = $returnedData['repertoire'];
        $musics = $returnedData['musics'];
        $singers = $returnedData['singers'];

        $this->assertEquals($repertoire['id'], $repertoire['id']);
        $this->assertEquals($repertoire['title'], $repertoire['title']);
        $this->assertEquals($repertoire['date'], $repertoire['date']);

        $this->assertEquals($music->id, $musics[0]['id']);
        $this->assertEquals($singer->id, $singers[0]['id']);
    }

    public function testRepertoireShowOutherUserNotFoundError(): void
    {
        $repertoire = Repertoire::factory()->create();

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->get("api/repertoire/{$repertoire->id}", []);

        $message = $response->json()['message'];

        $response->assertStatus(404);
        $this->assertEquals('This repertoire not found.', $message);
    }
}
