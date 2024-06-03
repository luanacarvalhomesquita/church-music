<?php

namespace Tests\Feature\User;

use App\Models\ResetsPassword;
use App\Models\User;
use Illuminate\Http\JsonResponse as Response;
use Tests\TestCase;

class PinCheckFTest extends TestCase
{
    public function testPinCheckWithEmptyFiledsError(): void
    {
        $payload = [];

        $response = $this->post('api/user/forgot-password/pin', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid([
            'pin' => 'PIN is required.',
            'email' => 'Email is required.',
        ]);
    }

    public function testPinCheckWithInvalidEmailReturnError(): void
    {
        $invalidEmail = 'invalid-email';

        $payload = [
            'email' => $invalidEmail,
        ];

        $response = $this->post('api/user/forgot-password/pin', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid(['email' => 'Email must have valid email.']);
    }

    public function testPinCheckWithInvalidUserReturnError(): void
    {
        $payload = ['email' => 'testeinvalido@gmail.com'];

        $response = $this->post('api/user/forgot-password/pin', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid(['email' => 'Invalid email.']);
    }

    public function testPinCheckWithInvalidPinReturnSuccess(): void
    {
        $email = 'teste@gmail.com';

        User::factory(User::class)->create([
            'email' => $email,
            'email_verified_at' => null,
        ]);

        ResetsPassword::factory(ResetsPassword::class)->create([
            'email' => $email,
            'pin' => '1234',
        ]);

        $payload = [
            'email' => $email,
            'pin' => 'invalid',
        ];

        $response = $this->post('api/user/forgot-password/pin', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function testPinCheckWithValidEmailReturnSuccess(): void
    {
        $email = 'teste@gmail.com';

        User::factory(User::class)->create([
            'email' => $email,
            'email_verified_at' => null,
        ]);

        $payload = [
            'email' => $email,
            'pin' => '1234',
        ];

        ResetsPassword::factory(ResetsPassword::class)->create($payload);

        $response = $this->post('api/user/forgot-password/pin', $payload);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
