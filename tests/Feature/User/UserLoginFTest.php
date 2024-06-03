<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Http\JsonResponse as Response;
use Tests\TestCase;

class UserLoginFTest extends TestCase
{
    public function testUserLoginWithEmptyFieldsError(): void
    {
        $payload = [];

        $response = $this->post('api/user/login', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid([
            'email' => 'Email is required.',
            'password' => 'Password is required.',
        ]);
    }

    public function testUserLoginWithInvalidEmailReturnError(): void
    {
        $password = '123abc';
        $invalidEmail = 'invalid-email';

        $payload = [
            'email' => $invalidEmail,
            'password' => $password,
        ];

        $response = $this->post('api/user/login', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid([
            'email' => 'Email must have valid email.',
        ]);
    }

    public function testUserLoginWithInvalidTypePasswordReturnError(): void
    {
        $password = 12312;
        $email = 'test@gmail.com';

        $payload = [
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->post('api/user/login', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid([
            'password' => 'This field must have a text.',
        ]);
    }

    public function testUserLoginWithInvalidUserReturnError(): void
    {
        $payload = [
            'email' => 'testeinvalido@gmail.com',
            'password' => '123abc',
        ];

        $response = $this->post('api/user/login', $payload);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $message = $response->decodeResponseJson()['message'];
        $this->assertEquals('The invalid e-mail or password.', $message);
    }

    public function testUserLoginWithInvalidPasswordReturnError(): void
    {
        $invalidPassword = '123abc';
        $email = 'teste@gmail.com';

        User::create([
            'name' => 'Luana',
            'email' => $email,
            'password' => '123456',
        ]);

        $payload = [
            'email' => $email,
            'password' => $invalidPassword,
        ];

        $response = $this->post('api/user/login', $payload);

        $response->assertStatus(401);
        $message = $response->decodeResponseJson()['message'];
        $this->assertEquals('The invalid e-mail or password.', $message);
    }

    public function testUserLoginReturnSuccess(): void
    {
        $password = '123abc';
        $email = 'teste@gmail.com';

        User::factory(User::class)->create([
            'name' => 'Luana Mesquita',
            'email' => $email,
            'password' => bcrypt($password),
            'email_verified_at' => null,
        ]);

        $payload = [
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->post('api/user/login', $payload);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];

        $this->assertArrayHasKey('token', $data);
    }
}
