<?php

namespace App\Service\Modules;

interface IHomeService
{
    public function GetTopPicks();
    public function Search($search, $page = 1);
    public function GetStoryByGenre($genre_id);
}