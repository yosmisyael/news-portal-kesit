<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Headline extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'headlines';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
    ];
}
