<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface HeadlineService
{
    public function all(): Collection;

    public function findById(string $id): Model|null;

    public function save(string $title): string|null;

    public function update(string $id, string $title): bool;

    public function delete(string $id): void;
}
