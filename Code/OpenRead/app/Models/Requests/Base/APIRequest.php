<?php

namespace App\Models\Requests\Base;

use App\Models\Requests\PostRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class APIRequest extends PostRequest
{
    /**
     * If validator fails return the exception in json form
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $errorResult = $this->setErrorResult();
        throw new HttpResponseException(response()->json(
            array_merge($errorResult, ['errors' => $validator->errors()])
        , 422));
    }

    /**
     * Set Error Result if validator fails
     * 
     * @return array
     */
    protected function setErrorResult()
    {
        return [];
    }
}
