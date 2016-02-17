<?php

namespace App\Http\Requests;

use App\Exceptions\ApiValidationException;
use Dingo\Api\Http\FormRequest;
use Dingo\Api\Http\Request;
use Illuminate\Contracts\Validation\Validator;

class ApiRequest extends FormRequest
{
    use ValidationMessages;

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->container['request'] instanceof Request) {
            throw new ApiValidationException($validator->errors(), $this->getFailedValidationMessage($this->container['request']));
        }

        parent::failedValidation($validator);
    }
}
