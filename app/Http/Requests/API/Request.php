<?php

namespace App\Http\Requests\API;

use App\Exceptions\ApiValidationException;
use App\Http\Requests\ValidationMessages;
use Dingo\Api\Http\FormRequest;
use Dingo\Api\Http\Request as BaseRequest;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
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
        if ($this->container['request'] instanceof BaseRequest) {
            throw new ApiValidationException($validator->errors(), $this->getFailedValidationMessage($this->container['request']));
        }

        parent::failedValidation($validator);
    }
}
