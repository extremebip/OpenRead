<?php

namespace App\Repository\Repositories;

use App\Models\DB\User;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct() {
        parent::__construct(new User());
    }

    public function FindByUsername($username)
    {
        return User::where('username', '=', $username)->first();
    }

    public function FindByEmail($email)
    {
        return User::where('email', '=', $email)->first();
    }

    public function FindAllPaginate($search, $offset, $limit)
    {
        return User::where('username', 'like', $search)
                    ->orWhere('name', 'like', $search)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
    }

    public function FindAllByUsernames($usernames)
    {
        return User::whereIn('username', $usernames)->get();
    }
}
