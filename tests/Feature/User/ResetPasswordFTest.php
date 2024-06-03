<?php

namespace Tests\Feature\User;

use App\Models\ResetsPassword;
use App\Models\User;
use Illuminate\Http\JsonResponse as Response;
use Tests\TestCase;

class ResetPasswordFTest extends TestCase
{
    public function testResetPasswordWithEmptyFiledsError(): void
    {
        $payload = [];

        $response = $this->post('api/user/forgot-password/new-password', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid([
            'pin' => 'PIN is required.',
            'email' => 'Email is required.',
            'password' => 'Password is required.',
        ]);
    }

    public function testResetPasswordWithInvalidEmailError(): void
    {
        $invalidEmail = 'invalid-email';

        $payload = [
            'email' => $invalidEmail,
            'password' => '123abc',
            'password_confirmed' => '123abc',
            'pin' =>  '1234'

        ];

        $response = $this->post('api/user/forgot-password/new-password', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid(['email' => 'Email must have valid email.']);
    }

    public function testResetPasswordWithInvalidPinError(): void
    {
        $payload = [
            'email' => 'teste@exemple.com',
            'password' => '123abc',
            'password_confirmation' => '123abc',
            'pin' =>  '1234',
        ];

        $response = $this->post('api/user/forgot-password/new-password', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid(['pin' => 'Invalid fields.']);
    }

    public function testResetPasswordDontMatchPasswordError(): void
    {
        $email = 'teste@exemple.com';
        $pin = '1234';

        ResetsPassword::factory(ResetsPassword::class)->create([
            'email' => $email,
            'pin' => $pin,
        ]);

        $payload = [
            'email' => $email,
            'password' => '123abc',
            'password_confirmation' => '321abc',
            'pin' =>  $pin,
        ];

        $response = $this->post('api/user/forgot-password/new-password', $payload);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertInvalid(['password' => "Passwords don't match."]);
    }

    public function testResetPasswordExpiredPinError(): void
    {
        $email = 'teste@exemple.com';
        $pin = '1234';

        User::factory(User::class)->create([
            'email' => $email,
        ]);

        ResetsPassword::factory(ResetsPassword::class)->create([
            'email' => $email,
            'pin' => $pin,
            'expired_date' =>  '2022-11-15 00:00:00',
        ]);

        $payload = [
            'email' => $email,
            'password' => '123abc',
            'password_confirmation' => '123abc',
            'pin' =>  $pin,
        ];

        $response = $this->post('api/user/forgot-password/new-password', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
     
        $this->assertDatabaseMissing('password_resets', [
            'email' => $email,
        ]);
    }

    public function testResetPasswordSuccess(): void
    {
        $email = 'teste@exemple.com';
        $pin = '1234';

        User::factory(User::class)->create([
            'email' => $email,
        ]);

        ResetsPassword::factory(ResetsPassword::class)->create([
            'email' => $email,
            'pin' => $pin,
        ]);

        $payload = [
            'email' => $email,
            'password' => '123abc',
            'password_confirmation' => '123abc',
            'pin' =>  $pin,
        ];

        $response = $this->post('api/user/forgot-password/new-password', $payload);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }
}
