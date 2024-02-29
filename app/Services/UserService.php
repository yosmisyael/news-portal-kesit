<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserService
{
    public function all(): Collection;

    public function findById(string $id): ?Model;

    public function findByUsername(string $username);

    public function findCurrentUser(): Authenticatable;

    public function save(string $username, string $name, string $email, string $password): ?User;

    public function update(string $id, array $data): bool;

    public  function delete(string $id): bool|null;
}
