<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryGenre extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'genre_id',
    ];
}
