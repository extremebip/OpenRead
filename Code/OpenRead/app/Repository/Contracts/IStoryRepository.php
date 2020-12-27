<?php

namespace App\Repository\Contracts;

interface IStoryRepository
{
    public function FindAllByUser($username);
}