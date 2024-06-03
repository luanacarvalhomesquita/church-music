<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public const NO_NAME = 'No Name';

    /**
     * @param User $users
     * @return void
     */
    public function __construct(
        protected readonly User $users
    ) {
    }

    public function getNameByEmail(string $email): string
    {
        $user = $this->users->where('email', $email)->first();

        return $user ? $user['name'] : self::NO_NAME;
    }

    public function register(array $fields): void
    {
        $this->users->create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
    }

    public function login(string $email, string $password): ?string
    {
        $user = $this->users->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $this->generateToken($user);
    }

    private function generateToken(User $user): string
    {
        return $user->createToken('testeToken')->plainTextToken;
    }
}
