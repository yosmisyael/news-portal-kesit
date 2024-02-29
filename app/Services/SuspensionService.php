<?php

namespace App\Services;

interface SuspensionService
{
    public function save(string $submissionId, string $violation): string|null;
}
