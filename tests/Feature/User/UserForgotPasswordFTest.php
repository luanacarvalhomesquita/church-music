<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Http\JsonResponse as Response;
use Tests\TestCase;

class UserForgotPasswordFTest extends TestCase
{
    public function testForgotPasswordWithEmptyFieldsError(): void
    {
        $payload = [];

        $response = $this->post('api/user/forgot-password', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid(['email' => 'Email is required.']);
    }

    public function testForgotPasswordWithInvalidEmailReturnError(): void
    {
        $invalidEmail = 'invalid-email';

        $payload = [
            'email' => $invalidEmail,
        ];

        $response = $this->post('api/user/forgot-password', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid(['email' => 'Email must have valid email.']);
    }

    public function testForgotPasswordWithInvalidUserReturnSuccess(): void
    {
        $payload = ['email' => 'testeinvalido@gmail.com'];

        $response = $this->post('api/user/forgot-password', $payload);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testForgotPasswordWithValidEmailReturnSuccess(): void
    {
        $email = 'teste@gmail.com';

        User::factory(User::class)->create([
            'name' => 'Luana Mesquita',
            'email' => $email,
            'email_verified_at' => null,
        ]);

        $payload = ['email' => $email];

        $response = $this->post('api/user/forgot-password', $payload);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
