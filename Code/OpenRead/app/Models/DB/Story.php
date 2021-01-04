<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'story_id',
        'username',
        'story_title',
        'cover',
        'status',
        'sinopsis',
        'views',
    ];

    protected $primaryKey = 'story_id';

    public $incrementing = false;

    protected $keyType = 'string';
}
