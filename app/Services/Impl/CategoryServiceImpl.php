<?php

namespace App\Services\Impl;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryServiceImpl implements \App\Services\CategoryService
{

    public function all(): Collection
    {
        return Category::query()->withCount('posts')->get();
    }

    public function findById(string $id): ?Model
    {
        return Category::query()->find($id);
    }

    public function save(string $name): ?string
    {
        $category = new Category([
            'name' => $name
        ]);

        return $category->save() ? $category->id : null;
    }

    public function update(string $id, array $data): bool
    {
        return Category::query()->find($id)->update($data);
    }

    public function delete(string $id): bool|null
    {
        return Category::query()->find($id)->delete();
    }
}
