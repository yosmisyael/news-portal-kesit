<?php

namespace App\Services\Impl;

use App\Enums\PostStatusEnum;
use App\Models\Review;

class ReviewServiceImpl implements \App\Services\ReviewService
{

    public function save(string $submissionId, PostStatusEnum $status, string $messages): string|null
    {
        $review = new Review([
            'submission_id' => $submissionId,
            'messages' => $messages,
            'status' => $status,
        ]);

        return $review->save() ? $review->id : null;
    }
}
