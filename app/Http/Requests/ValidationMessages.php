<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait ValidationMessages
{
    /**
     * Get the resource name.
     *
     * @return string
     */
    public function getResourceName()
    {
        return property_exists($this, 'resource') ? $this->resource : 'resource';
    }

    /**
     * Get the failed validation message for the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string|null
     */
    public function getFailedValidationMessage(Request $request)
    {
        $messages = method_exists($this, 'failedValidationMessages') ? $this->failedValidationMessages() : [];
        $method = strtoupper($request->method());
        $path = $request->path();

        return array_get($messages, "{$method}.{$path}", $this->fallbackValidationMessages($method));
    }

    /**
     * List of the default failed validation messages.
     *
     * @param  string $method
     * @return string|null
     */
    protected function fallbackValidationMessages($method)
    {
        $data = ['resource' => $this->getResourceName()];

        $messages = [
            SymfonyRequest::METHOD_GET    => trans('api.can_not_find_resource', $data),
            SymfonyRequest::METHOD_POST   => trans('api.can_not_create_resource', $data),
            SymfonyRequest::METHOD_PUT    => trans('api.can_not_update_resource', $data),
            SymfonyRequest::METHOD_PATCH  => trans('api.can_not_update_resource', $data),
            SymfonyRequest::METHOD_DELETE => trans('api.can_not_delete_resource', $data),
        ];

        return isset($messages[$method]) ? $messages[$method] : null;
    }
}
