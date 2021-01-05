<?php

namespace App\Service\Modules;

use App\Models\DB\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Repository\Contracts\IUserRepository;
use App\Repository\Contracts\IStoryRepository;
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

    public function StoreImage($data)
    {
        $image = $data['profile_picture'];
        $newFileName = (string) Str::uuid();
        $temporaryName = $newFileName.'.'.$image->extension();
        Storage::putFileAs('temp/', $image, $temporaryName);
        return [
            'name' => $temporaryName,
            'url' => route('preview-image-profile', ['name' => $temporaryName, 'temp' => 'true'])
        ];
    }

    public function CheckImageExist($name, $directory = 'profilePic/')
    {
        $result = ['found' => false];
        $filePath = $directory.$name;
        if (!Storage::exists($filePath))
            return $result;

        $result['found'] = true;
        $result['path'] = storage_path('app/'.$filePath);
        $result['ext'] = pathinfo($result['path'], PATHINFO_EXTENSION);
        return $result;
    }

    public function UpdateProfile($data)
    {
        $user = $this->userRepository->Find($data['username']);

        $user->name = $data['name'];
        if (isset($data['email'])){
            $user->email = $data['email'];
        }
        if (!is_null($data['new_image'])){
            if (!is_null($user->profile_picture)){
                $filePath = 'profilePic/'.$user->profile_picture;
                if (Storage::exists($filePath))
                    Storage::delete($filePath);
            }
            Storage::move('temp/'.$data['new_image'], 'profilePic/'.$data['new_image']);
            $user->profile_picture = $data['new_image'];
        }

        return $this->userRepository->InsertUpdate($user);
    }
}
