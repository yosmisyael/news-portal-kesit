<?php

namespace App\Services;

interface AdminAuthService
{
    public function login(string $username, string $password): bool;

    public function logout(): void;
}
