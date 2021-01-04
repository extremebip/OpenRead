<?php

namespace App\Models\Requests\Auth;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Models\Requests\PostRequest;

class SignUpPostRequest extends PostRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $today = Carbon::now()->toDateString();
        return [
            'name' => ['required', 'string', 'min:6', 'max:50'],
            'username' => ['required', 'string', 'min:6', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'dob' => ['required', 'date', 'before_or_equal:'.$today],
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
            'name.min' => 'Name must have at least :min characters',

            'username.required' => 'Username must not be empty',
            'username.min' => 'Username must have at least :min characters',
            'username.max' => 'Username must not have more than :max characters',
            'username.unique' => 'Username has been taken',

            'email.required' => 'Email must not be empty',
            'email.email' => 'Email does not match email format',
            'email.max' => 'Email must not have more than :max characters',
            'email.unique' => 'There is an existing email registered',

            'dob.required' => 'Date of Birth must not be empty',
            'dob.date' => 'Date of Birth is not in valid date input',
            'dob.before_or_equal' => 'Date of Birth must not exceed today',

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