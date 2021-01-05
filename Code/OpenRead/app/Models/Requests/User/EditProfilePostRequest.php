<?php

namespace App\Models\Requests\User;

use App\Models\Requests\PostRequest;
use App\Service\Contracts\IUserProfileService;

class EditProfilePostRequest extends PostRequest
{
    private $userProfileService;

    public function __construct(IUserProfileService $userProfileService) {
        $this->userProfileService = $userProfileService;
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
            'new_image' => ['present', function ($attribute, $value, $fail)
            {
                $result = $this->userProfileService->CheckImageExist($value, 'temp/');
                if (!$result['found']){
                    $fail('Uploaded image cannot be saved. Please upload again');
                }
            }],
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
        ];
    }

    public function validated()
    {
        $data = parent::validated();
        return array_merge(parent::validated(), [
            'username' => $this->user()->username
        ]);
    }
}
