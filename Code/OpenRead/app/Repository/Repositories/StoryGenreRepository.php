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

    public function FindAllByStory($story_id)
    {
        return StoryGenre::where('story_id', '=', $story_id)->get();
    }
}
