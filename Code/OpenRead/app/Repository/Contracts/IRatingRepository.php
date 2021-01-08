<?php

namespace App\Repository\Contracts;

interface IRatingRepository
{
    public function FindAllByStories($story_ids);
    public function FindByStoryAndUser($story_id, $username);
}