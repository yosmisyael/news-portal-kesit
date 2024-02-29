<?php

namespace App\Services\Impl;

use App\Services\UserAuthService;
use Illuminate\Support\Facades\Auth;

class UserAuthServiceImpl implements UserAuthService
{
    public function login(string $username, string $password): bool
    {
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        return Auth::attempt($credentials);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
