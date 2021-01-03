<?php

namespace App\Repository\Contracts;

interface IUserRepository
{
    public function FindByUsername($username);
    public function FindByEmail($email);
    public function FindAllPaginate($search, $offset, $limit);
}