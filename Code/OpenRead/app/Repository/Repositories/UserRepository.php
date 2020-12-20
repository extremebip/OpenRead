<?php

namespace App\Repository\Repositories;

use App\Models\DB\User;
use App\Repository\Base\BaseRepository;

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
}
