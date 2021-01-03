<?php

namespace App\Repository\Contracts;

interface IGenreRepository
{
    public function FindAllByIDs($genre_ids);
}