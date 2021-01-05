<?php

namespace App\Models\DB;

use App\Models\DB\Base\CompositeKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory, CompositeKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'story_id',
        'username',
        'rate',
    ];

    protected $primaryKey = ['story_id', 'username'];

    public $timestamps = false;

    public function id()
    {
        return [$this->story_id, $this->username];
    }
}
