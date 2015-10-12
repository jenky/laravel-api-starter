<?php

namespace App\Http\Requests;

use App\Exceptions\ApiValidationException;
use Dingo\Api\Http\FormRequest;
use Dingo\Api\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ApiRequest extends FormRequest
{
    /**
     * The resource name for the validation message.
     * 
     * @var string
     */
    protected $resource = 'resource';

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
            throw new ApiValidationException($validator->errors(), $this->buildFailedValidationMessage($this->container['request']));
        }

        parent::failedValidation($validator);
    }

    /**
     * Build the failed validation message for the response
     * based on standard REST method.
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return string|null
     */
    protected function buildFailedValidationMessage(Request $request)
    {
        return $this->getfailedValidationMessage($method);
    }

    /**
     * Get the failed validation message for the response.
     * 
     * @param string $method
     * 
     * @return string|null
     */
    protected function getfailedValidationMessage($method)
    {
        $messages = $this->failedValidationMessage();
        $method = strtoupper($method);

        return isset($messages[$method]) ? $messages[$method] : null;
    }

    /**
     * List of the failed validation messages.
     * 
     * @return array
     */
    protected function failedValidationMessage()
    {
        return [
            SymfonyRequest::METHOD_GET    => 'Can not retrive '.$this->resource,
            SymfonyRequest::METHOD_POST   => 'Can not create new '.$this->resource,
            SymfonyRequest::METHOD_PUT    => 'Can not update '.$this->resource,
            SymfonyRequest::METHOD_PATCH  => 'Can not update '.$this->resource,
            SymfonyRequest::METHOD_DELETE => 'Can not delete '.$this->resource,
        ];
    }
}
