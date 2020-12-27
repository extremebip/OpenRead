<?php

namespace App\Service\Contracts;

interface IUserProfileService
{
    public function GetUserWithStories($username);
    public function GetUser($username);
    public function UpdateProfile($data);
}