<?php

namespace App\Models\Requests\User;

use App\Models\Requests\PostRequest;
use Illuminate\Validation\Rule;

class EditProfilePostRequest extends PostRequest
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
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'gender' => ['required', 'string', 'max:1', Rule::in(['M', 'F'])],
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

            'email.required' => 'Email must not be empty',
            'email.email' => 'Email does not match email format',
            'email.max' => 'Email must not have more than :max characters',
            'email.unique' => 'There is an existing email registered',

            'gender.required' => 'Gender must not be empty',
            'gender.max' => 'Gender input does not meet format',
            'gender.in' => 'Selected Gender is invalid',
        ];
    }

    public function validated()
    {
        return array_merge(parent::validated(), [
            'username' => $this->user()->username
        ]);
    }
}
