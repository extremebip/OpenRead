<?php

namespace App\Repository\Repositories;

use App\Models\DB\Rating;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IRatingRepository;

class RatingRepository extends BaseRepository implements IRatingRepository
{
    public function __construct() {
        parent::__construct(new Rating());
    }

    public function FindAllByStories($story_ids)
    {
        return Rating::whereIn('story_id', $story_ids)->get();
    }

    public function FindByStoryAndUser($story_id, $username)
    {
        return Rating::where('story_id', '=', $story_id)
                     ->where('username', '=', $username)
                     ->first();
    }
}
