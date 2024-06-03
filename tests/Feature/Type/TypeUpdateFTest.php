<?php

namespace Tests\Feature\Type;

use App\Models\Type;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Tests\TestCase;

class TypeUpdateFTest extends TestCase
{
    public function testTypeUpdateSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Adorração',
            'user_id' => $user->id,
        ]);

        $request = ['name' => 'Adoração'];

        $response = $this->actingAs($user)->post("api/type/{$type->id}", $request);

        $response->assertStatus(204);
    }

    public function testTypeUpdateNoAccess(): void
    {
        $type = Type::factory()->create(['name' => 'Adorração']);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $params = ['name' => 'Adoração'];

        $response = $this->actingAs($user)->post("api/type/{$type->id}", $params);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('types', [
            'id' => $type['id'],
            'name' => $params['name'],
        ]);
        $this->assertDatabaseHas('types', [
            'id' => $type['id'],
            'name' => $type['name'],
        ]);
    }

    public function testTypeUpdateSelfEqualNameSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $type = Type::factory()->create([
            'name' => 'Adoração',
            'user_id' => $user->id,
        ]);

        $request = ['name' => 'Adoração'];

        $response = $this->actingAs($user)->post("api/type/{$type->id}", $request);

        $response->assertStatus(204);
    }

    public function testTypeUpdateEqualNameError(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $name = 'Adoração';

        Type::factory()->create([
            'name' => $name,
            'user_id' => $user->id,
        ]);

        $type = Type::factory()->create([
            'name' => 'Celebração',
            'user_id' => $user->id,
        ]);


        $request = ['name' => $name];

        $response = $this->actingAs($user)->post("api/type/{$type->id}", $request);

        $response->assertStatus(Response::HTTP_CONFLICT);

        $message = $response->json()['message'];
        $this->assertEquals('This name already exists.', $message);
    }
}
