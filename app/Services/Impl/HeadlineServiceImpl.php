<?php

namespace App\Services\Impl;

use App\Models\Headline;
use App\Services\HeadlineService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class HeadlineServiceImpl implements HeadlineService
{

    public function all(): Collection
    {
        return Headline::all();
    }

    public function findById(string $id): Model|null
    {
        return Headline::query()->find($id);
    }

    public function save(string $title): string|null
    {
        $headline = new Headline([
            'title' => $title,
        ]);

        return $headline->save() ? $headline->id : null;
    }

    public function update(string $id, string $title): bool
    {
        return Headline::query()->find($id)->update([
            'title' => $title,
        ]);
    }

    public function delete(string $id): void
    {
        Headline::query()->find($id)->delete();
    }
}
