<?php

namespace Tests\Feature\Repertoire;

use App\Models\Music;
use App\Models\Repertoire;
use App\Models\Singer;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

class RepertoireStoreFTest extends TestCase
{
    public function testRepertoireStoreEqualsTitleAndDateError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $title = 'Culto da Familia';
        $date = '2022-01-01';

        Repertoire::factory()->create([
            'title' => $title,
            'date' => $date,
            'user_id' => $user->id,
        ]);

        $payload = [
            'title' => $title,
            'date' => $date,
            'musics' => [],
        ];

        $response = $this->actingAs($user)->post('api/repertoire', $payload);

        $message = $response->json()['message'];

        $response->assertStatus(409);
        $this->assertEquals('This title with this date already exists.', $message);
    }

    public function testRepertoireStoreEqualsOutherUserWithoutMusicAndSingerSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $title = 'Culto da Familia';
        $date = '2022-01-01';

        Repertoire::factory()->create([
            'title' => $title,
            'date' => $date,
        ]);

        $payload = [
            'title' => $title,
            'date' => $date,
            'musics' => [],
            'singers' => [],
        ];

        $response = $this->actingAs($user)->post('api/repertoire', $payload);

        $response->assertStatus(204);

        $this->assertDatabaseHas('repertoires', [
            'title' => $title,
            'date' => $date,
            'user_id' => $user->id,
        ]);
    }

    public function testRepertoireStoreWithMusicSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $title = 'Culto da Familia';
        $date = '2022-01-01';

        $music = Music::factory()->create([
            'user_id' => $user->id
        ]);

        $payload = [
            'title' => $title,
            'date' => $date,
            'musics' => [
                [
                    'id' => $music->id,
                    'tone' => 'A',
                ],
            ],
            'singers' => [],
        ];

        $response = $this->actingAs($user)->post('api/repertoire', $payload);

        $response->assertStatus(204);

        $this->assertDatabaseHas('repertoires', [
            'title' => $title,
            'date' => $date,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('music_repertoire', [
            'music_name' => $music->name,
            'music_id' => $music->id,
            'tone' =>  'A',
        ]);
    }

    public function testRepertoireStoreWithSingerSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $title = 'Culto da Familia';
        $date = '2022-01-01';

        $singer = Singer::factory()->create([
            'user_id' => $user->id
        ]);

        $payload = [
            'title' => $title,
            'date' => $date,
            'singers' => [
                [
                    'id' => $singer->id,
                ],
            ],
            'musics' => [],
        ];

        $response = $this->actingAs($user)->post('api/repertoire', $payload);

        $response->assertStatus(204);

        $this->assertDatabaseHas('repertoires', [
            'title' => $title,
            'date' => $date,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('singer_repertoire', [
            'singer_name' => $singer->name,
            'singer_id' => $singer->id,
        ]);
    }
}
