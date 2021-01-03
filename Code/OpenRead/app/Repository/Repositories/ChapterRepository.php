<?php

namespace App\Repository\Repositories;

use App\Models\DB\Chapter;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IChapterRepository;

class ChapterRepository extends BaseRepository implements IChapterRepository
{
    public function __construct() {
        parent::__construct(new Chapter());
    }

    public function FindAllByStory($story_id)
    {
        return Chapter::where('story_id', '=', $story_id)->get();
    }
}
