<?php

namespace App\Service\Modules;

use App\Models\DB\User;
use App\Service\Contracts\IAuthService;
use App\Repository\Contracts\IUserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService implements IAuthService
{
    private $userRepository;

    public function __construct(
        IUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function GetUserByEmailOrUsername($field){
        $user = $this->userRepository->FindByUsername($field);
        if (!is_null($user))
            return $user;

        $user = $this->userRepository->FindByEmail($field);
        if (!is_null($user))
            return $user;

        return null;
    }

    public function RegisterUser($data)
    {
        $user = new User();

        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->date_of_birth = $data['date_of_birth'];
        $user->gender = $data['gender'];
        $user->password = Hash::make($data['password']);

        return $this->userRepository->InsertUpdate($user);
    }
}
