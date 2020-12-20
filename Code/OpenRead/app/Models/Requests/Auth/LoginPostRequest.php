<?php

namespace App\Models\Requests\Auth;

use App\Models\Requests\PostRequest;

class LoginPostRequest extends PostRequest
{
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8'],
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
            'username.required' => 'Username must not be empty',
            'username.min' => 'Username must have at least :min characters',

            'password.required' => 'Password must not be empty',
            'password.min' => 'Password must have at least :min characters',
        ];
    }
}
