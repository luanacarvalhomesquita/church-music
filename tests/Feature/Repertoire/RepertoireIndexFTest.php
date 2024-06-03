<?php

namespace Tests\Feature\Repertoire;

use App\Models\Repertoire;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\Helper\RepertoireList;
use Tests\TestCase;

class RepertoireIndexFTest extends TestCase
{
    use RepertoireList;

    public function testRepertoireIndexSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $repertoire1 = Repertoire::factory()->create([
            'user_id' => $user->id,
            'title' => 'A Teste Primeiro',
            'date' => '2021-01-01',
        ]);
        $repertoire2 = Repertoire::factory()->create([
            'user_id' => $user->id,
            'title' => 'C Teste Terceiro',
            'date' => '2021-01-01',
        ]);
        $repertoire3 = Repertoire::factory()->create([
            'user_id' => $user->id,
            'title' => 'B Teste Segundo',
            'date' => '2021-01-01',
        ]);

        $this->createRepertoire(userId: $user->id, repertoireId: $repertoire1->id);
        $this->createRepertoire(userId: $user->id, repertoireId: $repertoire2->id);
        $this->createRepertoire(userId: $user->id, repertoireId: $repertoire3->id);

        $params = [
            'page' => 1,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/repertoire?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseRepertoires = $returnedData['repertoires'];
        
        $response->assertStatus(200);

        $this->assertEquals($repertoire1->id, $responseRepertoires[0]['id']);
        $this->assertEquals($repertoire3->id, $responseRepertoires[1]['id']);

        $this->assertEquals($returnedData['total'], 3);
        $this->assertEquals($returnedData['page'], $params['page']);
        $this->assertEquals($returnedData['size'], $params['size']);
    }

    public function testRepertoireIndexEmpty(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $params = [
            'page' => 1,
            'size' => 2,
        ];

        $response = $this->actingAs($user)->get(
            'api/repertoire?' .
                http_build_query($params)
        );

        $returnedData = $response->json()['data'];
        $responseRepertoires = $returnedData['repertoires'];

        $response->assertStatus(200);

        $this->assertEmpty($responseRepertoires);

        $this->assertEquals($returnedData['total'], 0);
        $this->assertEquals($returnedData['page'], $params['page']);
        $this->assertEquals($returnedData['size'], $params['size']);
    }
}
