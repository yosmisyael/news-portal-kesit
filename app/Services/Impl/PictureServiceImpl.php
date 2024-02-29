<?php

namespace App\Services\Impl;

use App\Models\Picture;
use App\Services\PictureService;

class PictureServiceImpl implements PictureService
{

    public function save(string $name, string $path, ?string $postId): string|null
    {
        $picture = new Picture([
            'name' => $name,
            'path' => $path,
            'post_id' => $postId ?? null,
        ]);

        return $picture->save() ? $picture->path : null;
    }

    public function update(string $id, array $data): bool
    {
        $picture = Picture::query()->find($id);

        return $picture->update([
            'post_id' => $data['postId'],
        ]);
    }
}
