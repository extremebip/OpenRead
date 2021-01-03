<?php

namespace App\Repository\Repositories;

use App\Models\DB\Genre;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IGenreRepository;

class GenreRepository extends BaseRepository implements IGenreRepository
{
    public function __construct() {
        parent::__construct(new Genre());
    }

    public function FindAllByIDs($genre_ids)
    {
        return Genre::whereIn('genre_id', $genre_ids)->get();
    }
}
