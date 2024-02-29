<?php

namespace App\Services;

interface CategoryPostService
{
    public function attachCategoriesToPost(string $postId, array $categoriesIds): void;
}
