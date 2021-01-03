<?php

namespace App\Models\Requests\User;

use App\Models\Requests\PostRequest;
use App\Service\Contracts\IUserProfileService;

class EditProfilePostRequest extends PostRequest
{
    private $maxProfileImageSize = 20000;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:50'],
            'profile_picture' => ['present', 'file', 'image', 'max:'.$this->maxCoverImageSize],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $old_email = $this->user()->email;
        $validator->sometimes('email', 
            ['required', 'string', 'email', 'max:50', 'unique:users'],
            function ($input) use ($old_email) {
                return strcmp($input['email'], $old_email) != 0;
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name must not be empty',
            'name.max' => 'Name must not have more than :max characters',
            'name.min' => 'Name must have at least :min characters',

            'email.required' => 'Email must not be empty',
            'email.email' => 'Email does not match email format',
            'email.max' => 'Email must not have more than :max characters',
            'email.unique' => 'There is an existing email registered',

            'profile_picture.file' => 'Profile Picture is not a valid image file',
            'profile_picture.image' => 'Profile Picture is not a valid image file',
            'profile_picture.max' => 'Profile Picture size must not exceed '.$this->getFileSizePostfix($this->maxCoverImageSize),
        ];
    }

    public function validated()
    {
        $data = parent::validated();
        return array_merge(parent::validated(), [
            'username' => $this->user()->username,
            'change_email' => (strcmp($data['email'], $this->user()->username) != 0)
        ]);
    }
}
