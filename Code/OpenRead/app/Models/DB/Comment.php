<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment_id',
        'chapter_id',
        'username',
        'content',
    ];

    protected $primaryKey = 'comment_id';

    public $incrementing = false;

    protected $keyType = 'string';
}
