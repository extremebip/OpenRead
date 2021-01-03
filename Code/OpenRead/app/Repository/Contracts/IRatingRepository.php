<?php

namespace App\Repository\Contracts;

interface IRatingRepository
{
    public function FindAllByStories($story_ids);
}