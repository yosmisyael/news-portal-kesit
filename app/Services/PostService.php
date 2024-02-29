<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PostService
{
    public function all(): Collection;

    public function getPublished(): Collection;

    public function findById(string $id): Model|null;

    public function findByUserId(string $id): Collection;

    public function findByTitle(string $title): Collection;

    public function save(string $title, string $content): string|null;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool|null;
}
