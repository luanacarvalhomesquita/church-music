<?php

namespace Tests\Feature\Repertoire;

use App\Models\Repertoire;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

class RepertoireUpdateFTest extends TestCase
{
    public function testRepertoireUpdateSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $repertoire = Repertoire::factory()->create([
            'title' => 'Culto da Familiaaaa',
            'date' => '2022-01-01',
            'user_id' => $user->id,
        ]);

        $request = [
            'title' => 'Culto da Familia',
            'date' => '2022-01-02',
        ];

        $response = $this->actingAs($user)->post("api/repertoire/{$repertoire->id}", $request);

        $response->assertStatus(204);

        $this->assertDatabaseHas('repertoires', [
            'id' => $repertoire['id'],
            'title' => $request['title'],
            'user_id' => $user->id,
        ]);
    }

    public function testRepertoireUpdateNoAccess(): void
    {
        $repertoire = Repertoire::factory()->create(['title' => 'Culto de Gratidao']);

        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $request = [
            'title' => 'Culto da Familia',
            'date' => '2022-01-01',
        ];

        $response = $this->actingAs($user)->post("api/repertoire/{$repertoire->id}", $request);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('repertoires', [
            'id' => $repertoire['id'],
            'title' => $request['title'],
        ]);
        $this->assertDatabaseHas('repertoires', [
            'id' => $repertoire['id'],
            'title' => $repertoire['title'],
        ]);
    }

    public function testRepertoireUpdateNameEqualsSelfUpdate(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $title = 'Culto da Familia';
        $date = '2022-01-01';

        $repertoire = Repertoire::factory()->create([
            'title' => $title,
            'date' => $date,
            'user_id' => $user->id,
        ]);

        $request = [
            'title' => $title,
            'date' => $date,
        ];

        $response = $this->actingAs($user)->post("api/repertoire/{$repertoire->id}", $request);

        $response->assertStatus(204);

        $this->assertDatabaseHas('repertoires', [
            'id' => $repertoire['id'],
            'title' => $request['title'],
            'date' => $request['date'],
            'user_id' => $user->id,
        ]);
    }
}
