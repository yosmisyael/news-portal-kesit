<?php

namespace App\Services\Impl;

use Auth;

class AdminAuthServiceImpl implements \App\Services\AdminAuthService
{

    public function login(string $username, string $password): bool
    {
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];
        return Auth::guard('admin')->attempt($credentials);
    }

    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
