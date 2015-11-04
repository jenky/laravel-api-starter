<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiCustomException;
use App\Exceptions\ApiValidationException;
use Dingo\Api\Exceptions\ResourceException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ApiController extends Controller
{
    use Helpers;

    /**
     * The resource name for the validation message.
     * 
     * @var string
     */
    protected $resource = 'resource';

    /**
     * Get the resource or response an not found error.
     * 
     * @param mixed $resource
     * @param int $id
     * @param string|null $message
     * 
     * @return mixed
     */
    protected function getResource($resource, $id, $message = null)
    {
        $resource = $resource->find($id);

        if (is_null($resource)) {
            $this->response->errorNotFound($message);
        }

        return $resource;
    }

    /**
     * Find resource by id using api helper.
     * 
     * @param mixed $resource
     * @param int   $id
     * @param string|null $message
     * 
     * @return \Illuminate\Http\Response
     */
    protected function findResource($resource, $id, $message = null)
    {
        $data = apihelper($resource)->find($id);

        if (is_null($data)) {
            $this->response->errorNotFound($message);
        }

        return response()->json($data);
    }

    /**
     * Update resource by id.
     * 
     * @param mixed $resource
     * @param int $id
     * @param array $data
     * @param string|null $message
     * 
     * @return \Illuminate\Http\Response
     */
    protected function updateResource($resource, $id, array $data, $message = null)
    {
        $resource = $this->getResource($resource, $id, $message);

        $resource->update($data);

        return response()->json($resource);
    }

    /**
     * Delete resource by id.
     * 
     * @param mixed $resource
     * @param mixed $id
     * 
     * @return \Illuminate\Http\Response
     */
    protected function deleteResource($resource, $id)
    {
        $resource->destroy($id);

        return response()->json('', 204);
    }

    /**
     * Throw the unprocessable entity exception.
     * 
     * @param string $message
     * @param \Illuminate\Support\MessageBag|array $errors
     * 
     * @throw \Dingo\Api\Exceptions\ResourceException
     */
    protected function errorUnprocessable($message = null, $errors = null)
    {
        throw new ResourceException($message, $errors);
    }

    /**
     * Throw the custom exception.
     * 
     * @param string $message
     * @param array $errors
     * @param int $code
     * 
     * @throw \App\Exceptions\ApiCustomException
     */
    protected function errorCustom($message = null, $errors = null, $code = null)
    {
        throw new ApiCustomException($message, $errors, $code);
    }

    /**
     * {@inheritdoc}
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ApiValidationException($validator->errors(), $this->getFailedValidationMessage($request->method()));
    }

    /**
     * Get the failed validation message for the response.
     * 
     * @param string $method
     * 
     * @return string|null
     */
    protected function getFailedValidationMessage($method)
    {
        $messages = $this->failedValidationMessages();
        $method = strtoupper($method);

        return isset($messages[$method]) ? $messages[$method] : null;
    }

    /**
     * List of the failed validation messages.
     * 
     * @return array
     */
    protected function failedValidationMessages()
    {
        return [
            SymfonyRequest::METHOD_GET    => trans('api.can_not_find_resource', ['resource' => $this->resource]),
            SymfonyRequest::METHOD_POST   => trans('api.can_not_create_resource', ['resource' => $this->resource]),
            SymfonyRequest::METHOD_PUT    => trans('api.can_not_update_resource', ['resource' => $this->resource]),
            SymfonyRequest::METHOD_PATCH  => trans('api.can_not_update_resource', ['resource' => $this->resource]),
            SymfonyRequest::METHOD_DELETE => trans('api.can_not_delete_resource', ['resource' => $this->resource]),
        ];
    }
}
