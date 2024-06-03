<?php

namespace Tests\Feature\Music;

use App\Models\Music;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\TestCase;

class MusicUpdateFTest extends TestCase
{
    public function testMusicUpdateSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $music = Music::factory()->create([
            'name' => 'Me Amaaa',
            'user_id' => $user->id,
        ]);

        $request = [
            'name' => 'Me Ama',
            'type_id' => null,
            'description' => 'Salmos 1:1',
            'main_version' => 'Principal',
            'played' => true,
        ];

        $response = $this->actingAs($user)->post("api/music/{$music->id}", $request);

        $response->assertStatus(204);

        $this->assertDatabaseHas('music', [
            'id' => $music['id'],
            'name' => $request['name'],
            'type_id' => $request['type_id'],
            'description' => $request['description'],
            'main_version' => $request['main_version'],
            'played' => $request['played'],
        ]);
    }

    public function testMusicUpdateNoAccess(): void
    {
        $music = Music::factory()->create(['name' => 'Me amaa']);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $params = [
            'name' => 'Me Ama',
            'type_id' => null,
            'description' => null,
            'main_version' => 'Versao A',
            'played' => null,
        ];

        $response = $this->actingAs($user)->post("api/music/{$music->id}", $params);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('music', [
            'id' => $music['id'],
            'name' => $params['name'],
        ]);
        $this->assertDatabaseHas('music', [
            'id' => $music['id'],
            'name' => $music['name'],
        ]);
    }

    public function testMusicUpdateSelfEqualNameSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $name = 'Me Ama';

        $music = Music::factory()->create([
            'name' => $name,
            'user_id' => $user->id,
        ]);

        $request = [
            'name' => $name,
            'type_id' => null,
            'description' => 'Salmos 1:1',
            'main_version' => 'Principal',
            'played' => true,
        ];

        $response = $this->actingAs($user)->post("api/music/{$music->id}", $request);

        $response->assertStatus(204);

        $this->assertDatabaseHas('music', [
            'id' => $music['id'],
            'name' => $request['name'],
            'type_id' => $request['type_id'],
            'description' => $request['description'],
            'main_version' => $request['main_version'],
            'played' => $request['played'],
        ]);
    }

    public function testMusicUpdateEqualNameError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $name = 'Me Ama';

        Music::factory()->create([
            'name' => $name,
            'user_id' => $user->id,
        ]);

        $music = Music::factory()->create([
            'name' => 'Nome Temporario',
            'user_id' => $user->id,
        ]);

        $request = [
            'name' => $name,
            'type_id' => null,
            'description' => 'Salmos 1:1',
            'main_version' => 'Principal',
            'played' => true,
        ];

        $response = $this->actingAs($user)->post("api/music/{$music->id}", $request);

        $response->assertStatus(Response::HTTP_CONFLICT);

        $message = $response->json()['message'];
        $this->assertEquals('This name already exists.', $message);

        $this->assertDatabaseHas('music', [
            'id' => $music['id'],
            'name' => $music['name'],
            'type_id' => $music['type_id'],
            'description' => $music['description'],
            'main_version' => $music['main_version'],
            'played' => $music['played'],
        ]);
    }
}
