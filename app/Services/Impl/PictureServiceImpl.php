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

        return $picture->save() ? $picture->id : null;
    }

    public function update(string $id, array $data): bool
    {
        return Picture::query()->find($id)->update($data);
    }
}
