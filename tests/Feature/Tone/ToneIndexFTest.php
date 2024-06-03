<?php

namespace Tests\Feature\Tone;

use App\Models\Music;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

class ToneIndexFTest extends TestCase
{
    public function testSingerIndexSuccess(): void
    {
        /**
         * @var Authenticatable $user 
         */
        $user = $this->signIn();

        $response = $this->actingAs($user)->get('api/tone');

        $tones = $response->json()['data'];

        $response->assertStatus(200);
        $this->assertEquals(Music::TONES, $tones);
    }
}
