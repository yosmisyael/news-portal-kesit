<?php

namespace App\Services\Impl;

use App\Models\Suspension;

class SuspensionServiceImpl implements \App\Services\SuspensionService
{

    public function save(string $submissionId, string $violation): string|null
    {
        $suspension = new Suspension([
            'submission_id' => $submissionId,
            'violation' => $violation,
        ]);

        return $suspension->save() ? $suspension->id : null;
    }
}
