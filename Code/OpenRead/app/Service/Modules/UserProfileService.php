<?php

namespace App\Service\Modules;

use App\Models\DB\User;
use App\Repository\Contracts\IStoryRepository;
use App\Repository\Contracts\IUserRepository;
use App\Service\Contracts\IUserProfileService;

class UserProfileService implements IUserProfileService
{
    private $storyRepository;
    private $userRepository;

    public function __construct(
        IStoryRepository $storyRepository,
        IUserRepository $userRepository
    ) {
        $this->storyRepository = $storyRepository;
        $this->userRepository = $userRepository;
    }

    public function GetUserWithStories($username)
    {
        $retVal = [
            'user' => null,
            'stories' => collect()
        ];
        $user = $this->userRepository->Find($username);
        if (!is_null($user)){
            $retVal['user'] = $user;
            $stories = $this->storyRepository->FindAllByUser($username);
            // if ($stories->count() > 0){
            //     $storyIds = $stories->pluck('story_id');
            // }
        }
        return $retVal;
    }

    public function GetUser($username)
    {
        return $this->userRepository->Find($username);
    }

    public function UpdateProfile($data)
    {
        $user = $this->userRepository->Find($data['username']);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->gender = $data['gender'];

        return $userRepository->InsertUpdate($user);
    }
}
