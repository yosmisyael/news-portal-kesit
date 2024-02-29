<?php

namespace App\Services;

interface PictureService
{
    public function save(string $name, string $path, string $postId): string|null;
}
