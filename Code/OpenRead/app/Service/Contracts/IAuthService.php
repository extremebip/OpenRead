<?php

namespace App\Service\Contracts;

interface IAuthService
{
    public function RegisterUser($data);
    public function ChangePassword($user_id, $new_password);
}