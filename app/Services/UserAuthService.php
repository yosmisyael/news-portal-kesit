<?php

namespace App\Services;

interface UserAuthService
{
    public function login(string $username, string $password): bool;

    public function logout(): void;
}
