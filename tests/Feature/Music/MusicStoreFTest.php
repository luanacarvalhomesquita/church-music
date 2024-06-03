<?php

namespace Tests\Feature\Music;

use App\Models\Music;
use App\Models\Type;
use App\Models\Singer;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\Feature\Helper\CreateMusicList;
use Tests\TestCase;

class MusicStoreFTest extends TestCase
{
    use CreateMusicList;

    public function testMusicStoreEqualsNamesError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Celebração',
            'user_id' => $user->id,
        ]);

        $musicName = 'Me Ama';
        $this->createMusicsFromUserFactories(
            [$musicName],
            $user->id,
            $type->id
        );

        $payload = [
            'name' => $musicName,
            'description' => 'Salmos 1:1',
            'main_version' => 'Aline Barros',
            'played' => 1,
            'type_id' => $type->id,
            'singers' => [],
        ];

        $response = $this->actingAs($user)->post('api/music', $payload);

        $message = $response->json()['message'];

        $response->assertStatus(Response::HTTP_CONFLICT);
        $this->assertEquals('This name already exists.', $message);
    }

    public function testMusicStoreNewNameSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Celebração',
            'user_id' => $user->id,
        ]);

        $payload = [
            'name' => 'Me Ama',
            'description' => 'Salmos 1:1',
            'main_version' => 'Aline Barros',
            'played' => 1,
            'type_id' => $type->id,
            'singers' => [],
        ];

        $response = $this->actingAs($user)->post('api/music', $payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('music', [
            'user_id' => $user->id,
            'name' => $payload['name'],
            'description' => $payload['description'],
            'main_version' => $payload['main_version'],
            'played' => $payload['played'],
            'type_id' => $payload['type_id'],
        ]);
    }

    public function testMusicStoreOutherUserSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Celebração',
            'user_id' => $user->id,
        ]);

        $musicName = 'Me Ama';
        Music::factory()->create([
            'name' => $musicName,
        ]);

        $payload = [
            'name' => $musicName,
            'description' => 'Salmos 1:1',
            'main_version' => 'Aline Barros',
            'played' => 1,
            'type_id' => $type->id,
            'singers' => [],
        ];

        $response = $this->actingAs($user)->post('api/music', $payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('music', [
            'user_id' => $user->id,
            'name' => $payload['name'],
            'description' => $payload['description'],
            'main_version' => $payload['main_version'],
            'played' => $payload['played'],
            'type_id' => $payload['type_id'],
        ]);
    }

    public function testMusicStoreWithInvalidTypeError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $typeOutherUser = Type::factory()->create();

        $payload = [
            'name' => 'Me Ama',
            'description' => 'Salmos 1:1',
            'main_version' => 'Aline Barros',
            'played' => 1,
            'type_id' => $typeOutherUser->id,
            'singers' => [],
        ];

        $response = $this->actingAs($user)->post('api/music', $payload);

        $message = $response->json()['message'];

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals('This type not found.', $message);
    }

    public function testMusicStoreWithOnlyFieldsRequiredSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $payload = [
            'name' => 'Me Ama',
            'description' => null,
            'main_version' => 'Versao A',
            'played' => null,
            'type_id' => null,
            'singers' => [],
        ];

        $response = $this->actingAs($user)->post('api/music', $payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('music', [
            'user_id' => $user->id,
            'name' => $payload['name'],
            'description' => $payload['description'],
            'main_version' => $payload['main_version'],
            'played' => $payload['played'],
            'type_id' => $payload['type_id'],
        ]);
    }

    public function testMusicStoreWithSingersSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $payload = [
            'name' => 'Me Ama',
            'description' => 'Salmos 1:1',
            'main_version' => 'Aline Barros',
            'played' => true,
            'type_id' => null,
        ];

        $response = $this->actingAs($user)->post('api/music', $payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('music', [
            'user_id' => $user->id,
            'name' => $payload['name'],
            'description' => $payload['description'],
            'main_version' => $payload['main_version'],
            'played' => $payload['played'],
            'type_id' => $payload['type_id'],
        ]);
    }
}
