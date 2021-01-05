<?php

namespace App\Models\DB;

use App\Models\DB\Base\CompositeKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoryGenre extends Model
{
    use HasFactory, CompositeKey;

    protected $fillable = [
        'story_id',
        'genre_id',
    ];

    protected $primaryKey = ['story_id', 'genre_id'];

    public $timestamps = false;

    public function id()
    {
        return [$this->story_id, $this->genre_id];
    }
}
