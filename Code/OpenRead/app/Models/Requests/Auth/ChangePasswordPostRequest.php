<?php

namespace App\Models\Requests\Auth;

use App\Models\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordPostRequest extends PostRequest
{
    protected $passwordNotMatchErrorMessage = 'Old Password does not match';
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $passwordNotMatchErrorMessage = $this->passwordNotMatchErrorMessage;
        return [
            'old_password' => ['required', 'string', function ($attribute, $value, $fail)
                use ($passwordNotMatchErrorMessage)
            {
                    $user = Auth::user();
                    if (!Hash::check($value, $user->password)){
                        $fail($passwordNotMatchErrorMessage);
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:old_password'],
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
            'old_password.required' => 'Old Password must not be empty',

            'password.required' => 'New Password must not be empty',
            'password.min' => 'New Password must have at lest :min characters',
            'password.confirmed' => 'Confirm Password does not match',
            'password.different' => 'New Password must be different from Old Password',
        ];
    }
}
