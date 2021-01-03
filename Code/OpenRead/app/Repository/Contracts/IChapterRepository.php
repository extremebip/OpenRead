<?php

namespace App\Repository\Contracts;

interface IChapterRepository
{
    public function FindAllByStory($story_id);
}