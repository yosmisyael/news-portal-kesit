<?php

namespace App\Policies;

use App\Enums\PostStatusEnum;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can see the model.
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function view(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can submit the post model.
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function submitPost(User $user, Post $post): bool
    {
        // Check if the post is owned by the user.
        if ($user->id !== $post->user_id) {
            return false;
        }

        $latestSubmission = $post->submissions->last();

        // Check if the latest submission does not have a review.
        if ($latestSubmission && !$latestSubmission->review) {
            return false;
        }

        // Check if the post has a submission and its review is approved.
        if ($latestSubmission && $latestSubmission->review->status === PostStatusEnum::APPROVED && !$latestSubmission->suspension) {
            return false;
        }

        return true;
    }

    /**
     * @param Admin $admin
     * @param Post $post
     * @return bool
     */
    public function suspendPost(Admin $admin, Post $post): bool
    {
        $latestSubmission = $post->submissions->last();

        if (!$latestSubmission) {
            return false;
        }

        if (!$latestSubmission->review) {
            return false;
        }

        if ($latestSubmission->review->status !== PostStatusEnum::APPROVED) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function update(User $user, Post $post): bool
    {
        // Check if the post is owned by the user.
        if ($user->id !== $post->user_id) {
            return false;
        }

        $latestSubmission = $post->submissions->last();

        // Check if the latest submission does not have a review.
        if ($latestSubmission && !$latestSubmission->review) {
            return false;
        }

        // Check if the latest submission review status is approved and does not have a suspension.
        if ($latestSubmission && $latestSubmission->review->status === PostStatusEnum::APPROVED && !$latestSubmission->suspension) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function delete(User $user, Post $post): bool
    {
        // Check if the post is owned by the user.
        if ($user->id !== $post->user_id) {
            return false;
        }

        $latestSubmission = $post->submissions->last();

        // Check if the latest submission does not have a review.
        if ($latestSubmission && !$latestSubmission->review) {
            return false;
        }

        // Check if the latest submission review status is approved and does not have a suspension.
        if ($latestSubmission && $latestSubmission->review->status === PostStatusEnum::APPROVED && !$latestSubmission->suspension) {
            return false;
        }

        return true;
    }
}
