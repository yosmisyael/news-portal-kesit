<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface SubmissionService
{
    public function all(): Collection;

    public function findById(string $id): Model|null;

    public function findByPostId(string $postId): Collection|null;

    public function save(string $postId): string|null;
}
