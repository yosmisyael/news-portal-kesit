<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'posts';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'post_id');
    }

    public function suspension(): HasOneThrough
    {
        return $this->hasOneThrough(Suspension::class, Submission::class);
    }

    protected static function booting(): void
    {
        parent::booting();

        self::creating(function (Post $post) {
            $post->slug = Str::slug($post->title, '-');
            $post->user_id = auth()->id();
        });
    }
}
