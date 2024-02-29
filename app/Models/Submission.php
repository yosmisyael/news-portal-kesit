<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'submissions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'post_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function suspension(): HasOne
    {
        return $this->hasOne(Suspension::class);
    }
}
