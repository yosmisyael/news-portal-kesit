<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CategoryService
{
    public function all(): Collection;

    public function findById(string $id): Model|null;

    public function save(string $name): string|null;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool|null;
}
