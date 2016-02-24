<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ApiCustomException;
use App\Exceptions\ApiValidationException;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\ValidationMessages;
use Dingo\Api\Exceptions\ResourceException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use Helpers, ValidationMessages;

    /**
     * Get the resource or response an not found error.
     * 
     * @param  mixed $resource
     * @param  int $id
     * @param  string|null $message
     * @return mixed
     */
    protected function getResource($resource, $id, $message = null)
    {
        $resource = $resource->find($id);

        if (is_null($resource)) {
            $this->response->errorNotFound($this->getResponseMessage($message));
        }

        return $resource;
    }

    /**
     * List resources using api helper.
     * 
     * @param  mixed $resource
     * @param  int|null $limit
     * @return \Illuminate\Http\Response
     */
    protected function listResources($resource, $limit = null)
    {
        $resources = $limit ? apihelper($resource)->paginate(intval($limit)) : apihelper($resource)->collection();

        return response()->json($resources);
    }

    /**
     * Find resource by id using api helper.
     * 
     * @param  mixed $resource
     * @param  int $id
     * @param  string|null $message
     * @return \Illuminate\Http\Response
     */
    protected function findResource($resource, $id, $message = null)
    {
        $data = apihelper($resource)->findOrFail($id);

        return response()->json($data);
    }

    /**
     * Update resource by id.
     * 
     * @param  mixed $resource
     * @param  int $id
     * @param  array $data
     * @param  string|null $message
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
     * @param  mixed $resource
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    protected function deleteResource($resource, $id)
    {
        $resource->destroy($id);

        return response()->json('', 204);
    }

    /**
     * Get the default response message.
     *
     * @param  string|null $message
     * @return string
     */
    protected function getResponseMessage($message = null)
    {
        if ($this->resource && is_null($message)) {
            return trans('error.resource_not_found', ['resource' => $this->resource]);
        }

        return $message;
    }

    /**
     * Throw the unprocessable entity exception.
     * 
     * @param  string $message
     * @param  \Illuminate\Support\MessageBag|array $errors
     * @throws \Dingo\Api\Exceptions\ResourceException
     */
    protected function errorUnprocessable($message = null, $errors = null)
    {
        throw new ResourceException($message, $errors);
    }

    /**
     * Throw the custom exception.
     * 
     * @param  string $message
     * @param  array $errors
     * @param  int $code
     * @throws \App\Exceptions\ApiCustomException
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
        throw new ApiValidationException($validator->errors(), $this->getFailedValidationMessage($request));
    }
}
