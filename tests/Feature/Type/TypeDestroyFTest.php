<?php

namespace Tests\Feature\Type;

use App\Models\Type;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\Feature\Helper\CreateMusicList;
use Tests\TestCase;

class TypeDestroyFTest extends TestCase
{
     use CreateMusicList;

    public function testTypeDestroyWithoutMusicSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Adoração',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete("api/type/{$type->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('types', [
            'id' => $type['id'],
            'name' => $type['name'],
        ]);
    }

    public function testTypeDestroyWithMusicSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Adoração',
            'user_id' => $user->id
        ]);

        $musics = $this->createMusicsFromUserFactories(
            userId: $user->id,
            typeId: $type['id']
        );

        $response = $this->actingAs($user)->delete("api/type/{$type->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('types', [
            'id' => $type['id'],
            'name' => $type['name'],
        ]);

        $this->assertDatabaseHas('music', [
            'id' => $musics[0]['id'],
            'name' => $musics[0]['name'],
            'type_id' => null,
        ]);

        $this->assertDatabaseHas('music', [
            'id' => $musics[1]['id'],
            'name' => $musics[1]['name'],
            'type_id' => null,
        ]);
    }

    public function testTypeDestroyOutherUserError(): void
    {
        $type = Type::factory()->create([
            'name' => 'Adoração',
        ]);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->delete("api/type/{$type->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('types', [
            'id' => $type['id'],
            'name' => $type['name'],
        ]);
    }
}
