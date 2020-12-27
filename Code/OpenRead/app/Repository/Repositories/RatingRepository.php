<?php

namespace App\Repository\Repositories;

use App\Models\DB\Rating;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IRatingRepository;
use Illuminate\Support\Facades\DB;

class RatingRepository extends BaseRepository implements IRatingRepository
{
    public function __construct() {
        parent::__construct(new Rating());
    }

    public function FindAveragesByStories($storyIds)
    {
        return DB::table('ratings')
                 ->selectRaw('story_id, AVG(rate)')
                 ->whereIn('story_id', $storyIds)
                 ->groupBy('story_id')
                 ->get();
    }
}
