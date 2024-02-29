<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'reviews';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'messages',
        'submission_id',
        'status',
    ];

    protected $casts = [
        'status' => PostStatusEnum::class,
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
