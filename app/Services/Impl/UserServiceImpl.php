<?php

namespace App\Services\Impl;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserServiceImpl implements UserService
{
    public function all(): Collection
    {
        return User::all();
    }

    public function findById(string $id): ?Model
    {
        return User::query()->find($id);
    }

    public function findByUsername(string $username): Model
    {
        return User::query()->where('username', $username)->first();
    }


    public  function findCurrentUser(): Authenticatable
    {
        return Auth::user();
    }

    public function save(string $username, string $name, string $email, string $password): ?User
    {
        $user = new User([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        return $user->save() ? $user : null;
    }

    public function update(string $id, array $data): bool
    {
        return User::query()->find($id)->update($data);
    }

    public function delete(string $id): bool|null
    {
        return User::query()->find($id)->delete();
    }
}
