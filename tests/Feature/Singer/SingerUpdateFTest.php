<?php

namespace Tests\Feature\Singer;

use App\Models\Singer;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\TestCase;

class SingerUpdateFTest extends TestCase
{
    public function testSingerUpdateSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singer = Singer::factory()->create([
            'name' => 'Anna',
            'user_id' => $user->id,
        ]);

        $request = ['name' => 'Ana'];

        $response = $this->actingAs($user)->post("api/singer/{$singer->id}", $request);

        $response->assertStatus(204);

        $this->assertDatabaseHas('singers', [
            'id' => $singer['id'],
            'name' => $request['name'],
        ]);
    }

    public function testSingerUpdateNoAccess(): void
    {
        $singer = Singer::factory()->create(['name' => 'Adorração']);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $params = ['name' => 'Maria'];

        $response = $this->actingAs($user)->post("api/singer/{$singer->id}", $params);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('singers', [
            'id' => $singer['id'],
            'name' => $params['name'],
        ]);
        $this->assertDatabaseHas('singers', [
            'id' => $singer['id'],
            'name' => $singer['name'],
        ]);
    }

    public function testSingerUpdateSelfSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $singer = Singer::factory()->create([
            'name' => 'Anna',
            'user_id' => $user->id,
        ]);

        $request = ['name' => 'Anna'];

        $response = $this->actingAs($user)->post("api/singer/{$singer->id}", $request);

        $response->assertStatus(204);

        $this->assertDatabaseHas('singers', [
            'id' => $singer['id'],
            'name' => $request['name'],
        ]);
    }

    public function testSingerUpdateNameEqualsError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        Singer::factory()->create([
            'name' => 'Anna',
            'user_id' => $user->id,
        ]);

        $singer = Singer::factory()->create([
            'name' => 'Ana',
            'user_id' => $user->id,
        ]);

        $request = ['name' => 'Anna'];

        $response = $this->actingAs($user)->post("api/singer/{$singer->id}", $request);

        $response->assertStatus(Response::HTTP_CONFLICT);
        $message = $response->json()['message'];

        $this->assertEquals('This name already exists.', $message);
    }
}
