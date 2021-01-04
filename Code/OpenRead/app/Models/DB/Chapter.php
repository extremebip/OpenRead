<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chapter_id',
        'story_id',
        'chapter_title',
        'content',
    ];

    protected $primaryKey = 'chapter_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    public function id()
    {
        return $this->chapter_id;
    }
}
