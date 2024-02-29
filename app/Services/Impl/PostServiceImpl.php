<?php

namespace App\Services\Impl;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PostServiceImpl implements PostService
{
    public function all(): Collection
    {
        return Post::with('submissions.review')->get();
    }

    public function getPublished(): Collection
    {
        return Post::with('submissions')
                ->whereHas('submissions', function ($query) {
                    $query->whereHas('review', function ($subquery) {
                        $subquery->where('status', 'approved');
                    })->WhereDoesntHave('suspension')
                        ->orderBy('created_at', 'desc');
                })
                ->get();
    }

    /**
     * Retrieves a Post model instance by its ID along with their categories.
     *
     * @param string $id The unique identifier of the Post.
     * @return Model|null The matching Post instance if found, otherwise null.
     */
    public function findById(string $id): Model|null
    {
        return Post::with('submissions.review', 'submissions.suspension', 'categories:name')->find($id);
    }

    public function findByUserId(string $id): Collection
    {
        return Post::with('user')->where('user_id', $id)->get();
    }

    public function save(string $title, string $content): string|null
    {
        $post = new Post([
            'title' => $title,
            'content' => $content,
        ]);

        return $post->save() ? $post->id : null;
    }

    public function update(string $id, array $data): bool
    {
        return Post::query()->find($id)->update($data);
    }

    public function delete(string $id): bool|null
    {
        return Post::query()->find($id)->delete();
    }

    public function findByTitle(string $title): Collection
    {
        return Post::with('categories', 'user')->where('title', $title)
            ->orWhere('title', 'like', '%' . $title . '%')
            ->get();
    }
}
