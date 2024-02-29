<?php

namespace App\Services\Impl;

use App\Models\Post;

class CategoryPostServiceImpl implements \App\Services\CategoryPostService
{

    public function attachCategoriesToPost(string $postId, array $categoriesIds): void
    {
        Post::query()->find($postId)->categories()->sync($categoriesIds);
    }
}
