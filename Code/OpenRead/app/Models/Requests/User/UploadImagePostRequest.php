<?php

namespace App\Models\Requests\User;

use App\Models\Requests\Base\APIRequest;
use App\Service\Contracts\IUserProfileService;

class UploadImagePostRequest extends APIRequest
{
    private $maxProfileImageSize = 20000;

    private $userProfileService;

    public function __construct(IUserProfileService $userProfileService) {
        $this->userProfileService = $userProfileService;
    }

    private function getFileSizePostfix($size)
    {
        if ($size >= 1000000){
            return floor($size / 1000000).'GB';
        }
        else if ($size >= 1000){
            return floor($size / 1000).'MB';
        }
        else {
            return $size.'KB';
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->validationData();
        return (!is_null($data['username']) && $this->user()->username == $data['username']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required'],
            'profile_picture' => ['required', 'file', 'image', 'max:'.$this->maxProfileImageSize],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'profile_picture.required' => 'Profile Picture must not be empty',
            'profile_picture.file' => 'Profile Picture is not a valid image file',
            'profile_picture.image' => 'Profile Picture is not a valid image file',
            'profile_picture.max' => 'Profile Picture size must not exceed '.$this->getFileSizePostfix($this->maxProfileImageSize),
        ];
    }
}
