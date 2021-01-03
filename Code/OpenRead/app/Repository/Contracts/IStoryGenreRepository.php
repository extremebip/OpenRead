<?php

namespace App\Repository\Contracts;

interface IStoryGenreRepository
{
    public function FindAllByStory($story_id);
    public function FindAllByGenre($genre_id);
}