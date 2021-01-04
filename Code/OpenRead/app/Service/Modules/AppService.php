<?php

namespace App\Service\Modules;

use App\Service\Contracts\IAppService;
use App\Repository\Contracts\IGenreRepository;

class AppService implements IAppService
{
    private $genreRepository;

    public function __construct(IGenreRepository $genreRepository) {
        $this->genreRepository = $genreRepository;
    }

    public function GetGenres()
    {
        $genres = $this->genreRepository->FindAll();
        return $genres->map(function ($item, $key)
        {
            return ['genre_id' => $item->genre_id, 'name' => $item->genre_type];
        });
    }
}
