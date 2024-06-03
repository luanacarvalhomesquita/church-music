<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class UserRegisterFTest extends TestCase
{
    public function testUserRegisterWithEmptyFieldsError(): void
    {
        $payload = [];

        $response = $this->post('api/user/register', $payload);

        $response->assertStatus(302);
        $response->assertInvalid([
            'name' => 'Name is required.',
            'email' => 'Email is required.',
            'password' => 'Password is required.',
        ]);
    }

    public function testUserRegisterWithMinNameReturnError(): void
    {
        $payload = [
            'name' => 'L',
            'email' => 'luana@email.com',
            'password' => '123abc',
            'password_confirmation' => '123abc',
        ];

        $response = $this->post('api/user/register', $payload);

        $response->assertStatus(302);
        $response->assertInvalid([
            'name' => 'Name must have at least 2 characters.'
        ]);
    }

    public function testUserRegisterWithMaxNameReturnError(): void
    {
        $payload = [
            'name' => 'Luana Santos Silva Test Big Text From Name Invalid Luana Santos Silva Test Big Text From Name Invalid',
            'email' => 'luana@email.com',
            'password' => '123abc',
            'password_confirmation' => '123abc',
        ];

        $response = $this->post('api/user/register', $payload);

        $response->assertStatus(302);
        $response->assertInvalid([
            'name' => 'Name must have at most 100 characters'
        ]);
    }

    public function testUserRegisterWithInvalidEmailReturnError(): void
    {
        $payload = [
            'name' => 'Luana Mesquita',
            'email' => 'invalid-email',
            'password' => '123abc',
            'password_confirmation' => '123abc',
        ];

        $response = $this->post('api/user/register', $payload);

        $response->assertStatus(302);
        $response->assertInvalid([
            'email' => 'Email must have valid email.'
        ]);
    }

    public function testUserRegisterWithoutConfirmedPasswordReturnError(): void
    {
        $payload = [
            'name' => 'Luana Mesquita',
            'email' => 'luana.mesquit456456a@gmail.com',
            'password' => '123abc',
        ];

        $response = $this->post('api/user/register', $payload);

        $response->assertStatus(302);
        $response->assertInvalid([
            'password' => 'Passwords don\'t match.'
        ]);
    }

    public function testUserRegisterWithInvalidTypeReturnError(): void
    {
        $payload = [
            'name' => 12313,
            'email' => 123213,
            'password' => 234234,
            'password_confirmation' => 234234,
        ];

        $response = $this->post('api/user/register', $payload);

        $response->assertStatus(302);
        $response->assertInvalid([
            'name' => 'This field must have a text.',
            'email' => 'Email must have valid email.',
            'password' => 'This field must have a text.',
        ]);
    }

    public function testUserRegisterReturnSuccess(): void
    {
        $payload = [
            'name' => 'Luana Mesquita',
            'email' => 'luana.mesquit456456a@gmail.com',
            'password' => '123abc',
            'password_confirmation' => '123abc',
        ];

        $response = $this->post('api/user/register', $payload);

        $response->assertStatus(201);
    }
}
