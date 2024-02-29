<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suspension extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'suspensions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'violation',
        'submission_id',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
