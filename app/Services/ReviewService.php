<?php

namespace App\Services;

use App\Enums\PostStatusEnum;

interface ReviewService
{
    public function save(string $submissionId, PostStatusEnum $status, string $messages): string|null;
}
