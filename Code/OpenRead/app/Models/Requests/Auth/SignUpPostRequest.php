<?php

namespace App\Models\Requests\Auth;

use App\Models\Requests\PostRequest;
use Illuminate\Validation\Rule;

class SignUpPostRequest extends PostRequst
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'gender' => ['required', 'string', 'max:1', Rule::in(['M', 'F'])],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
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
            'name.required' => 'Name must not be empty',
            'name.max' => 'Name must not have more than :max characters',

            'username.required' => 'Username must not be empty',
            'username.min' => 'Username must have at least :min characters',
            'username.max' => 'Username must not have more than :max characters',
            'username.unique' => 'Username has been taken',

            'email.required' => 'Email must not be empty',
            'email.email' => 'Email does not match email format',
            'email.max' => 'Email must not have more than :max characters',
            'email.unique' => 'There is an existing email registered',

            'gender.required' => 'Gender must not be empty',
            'gender.max' => 'Gender input does not meet format',
            'gender.in' => 'Selected Gender is invalid',

            'password.required' => 'Password must not be empty',
            'password.min' => 'Password must have at least :min characters',
            'password.confirmed' => 'Confirm Password does not match',
            'password.max' => 'Password must not have more than :max characters',
        ];
    }
}