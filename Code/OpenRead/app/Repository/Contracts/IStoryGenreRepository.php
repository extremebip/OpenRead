<?php

namespace App\Repository\Contracts;

interface IStoryGenreRepository
{
    public function FindAllByStory($story_id);
}