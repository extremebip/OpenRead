<?php

namespace App\Repository\Repositories;

use App\Models\DB\Story;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IStoryRepository;

class StoryRepository extends BaseRepository implements IStoryRepository
{
    public function __construct() {
        parent::__construct(new Story());
    }

    public function FindAllByUser($username)
    {
        return Story::where('username', '=', $username)->get();
    }
}
