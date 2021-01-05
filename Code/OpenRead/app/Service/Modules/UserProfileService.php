<?php

namespace App\Service\Modules;

use App\Models\DB\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Repository\Contracts\IUserRepository;
use App\Repository\Contracts\IStoryRepository;
use App\Service\Contracts\IUserProfileService;
use App\Repository\Contracts\IRatingRepository;

class UserProfileService implements IUserProfileService
{
    private $ratingRepository;
    private $storyRepository;
    private $userRepository;

    public function __construct(
        IRatingRepository $ratingRepository,
        IStoryRepository $storyRepository,
        IUserRepository $userRepository
    ) {
        $this->ratingRepository = $ratingRepository;
        $this->storyRepository = $storyRepository;
        $this->userRepository = $userRepository;
    }

    public function GetUserWithStories($username)
    {
        $result = [
            'user' => null,
            'stories' => collect()
        ];
        $user = $this->userRepository->Find($username);
        if (!is_null($user)){
            $takeLimit = 3;
            $result['user'] = $user;
            $stories = $this->storyRepository->FindAllByUserLimitByOrderBy($username, $takeLimit, 'story_id', 'desc');
            if ($stories->count() == 0){
                return $result;
            }
            $story_ids = $stories->pluck('story_id');
            $story_ratings = $this->ratingRepository->FindAllByStories($story_ids);
            $groupRatingsByStory = $story_ratings->groupBy('story_id');
            $mapStoryRatings = $groupRatingsByStory->mapWithKeys(function ($item, $key)
            {
                $temp = collect($item);
                return [$key => $temp->avg('rate')];
            })->sort();
            $result['stories'] = $stories->map(function ($item, $key) use($mapStoryRatings)
            {
                $rate = 0;
                if (isset($mapStoryRatings[$item->story_id]))
                    $rate = $mapStoryRatings[$item->story_id];
                return [
                    'title' => $item->story_title,
                    'rate' => $rate,
                    'views' => $item->views,
                    'cover' => $item->cover,
                    'synopsis' => $item->sinopsis
                ];
            });
        }
        return $result;
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
