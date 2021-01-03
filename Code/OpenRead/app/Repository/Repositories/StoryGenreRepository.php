<?php

namespace App\Repository\Repositories;

use App\Models\DB\StoryGenre;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IStoryGenreRepository;

class StoryGenreRepository extends BaseRepository implements IStoryGenreRepository
{
    public function __construct() {
        parent::__construct(new StoryGenre());
    }
}
