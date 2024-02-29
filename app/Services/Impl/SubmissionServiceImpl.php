<?php

namespace App\Services\Impl;

use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SubmissionServiceImpl implements SubmissionService
{

    /**
     * Retrieves all Submission model instances along with their related post,
     * filtering for those that do not have an associated review.
     *
     * @return Collection A collection of Submission models meeting the specified criteria.
     */
    public function all(): Collection
    {
        return Submission::with('review', 'post')
            ->whereDoesntHave('review')
            ->get();
    }

    /**
     * Finds a Submission model by its ID, eagerly loading related models.
     *
     * @param string $id The unique identifier of the Submission model.
     * @return Model|null Model|null The Submission model instance if found, otherwise null.
     */
    public function findById(string $id): Model|null
    {
        return Submission::with('review', 'suspension', 'post', 'post.user')->find($id);
    }

    /**
     * Finds a Submission model by post ID, eagerly loading related models.
     *
     * @param string $postId The unique identifier of the Post model.
     * @return Collection|null Model|null The Submission model instance if found, otherwise null.
     */
    public function findByPostId(string $postId): Collection|null
    {
        return Submission::with('review', 'suspension')->where('post_id', $postId)->get();
    }

    /**
     * Saves a new Submission model associated with the given post ID.
     *
     * @param string $postId string $postId The unique identifier of the post.
     * @return string|null string|null The ID of the newly saved Submission model, or null if saving failed.
     */
    public function save(string $postId): string|null
    {
        $submission = new Submission([
            'post_id' => $postId,
        ]);

        return $submission->save() ? $submission->id : null;
    }
}
