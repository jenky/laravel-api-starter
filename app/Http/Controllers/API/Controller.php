<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ApiCustomException;
use App\Exceptions\ApiValidationException;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\ValidationMessages;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    use Helpers, ValidationMessages;

    /**
     * Get the resource or response an not found error.
     *
     * @param  mixed $resource
     * @param  int $id
     * @param  callbable|null $callback
     * @return mixed
     */
    protected function getResource($resource, $id, callbable $callback = null)
    {
        $resource = $resource->find($id);

        if (is_null($resource) && ! is_null($callback)) {
            return $callback;
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

        return $resources;
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
        $resource = apihelper($resource)->findOrFail($id);

        return $resource;
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
        $resource = $resource->findOrFail($id);

        $resource->update($data);

        return $resource;
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

        return response('', 204);
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
     * Throws the unprocessable entity exception.
     *
     * @param  string $message
     * @param  \Illuminate\Support\MessageBag|array $errors
     * @return \Illuminate\Http\Response
     * @throws \Dingo\Api\Exception\ResourceException
     */
    public function errorUnprocessable($message = null, $errors = null)
    {
        throw new ResourceException($message, $errors);
    }

    /**
     * Throws the custom exception.
     *
     * @param  string $message
     * @param  array $errors
     * @param  int $code
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\ApiCustomException
     */
    public function errorCustom($message = null, $errors = null, $code = null)
    {
        throw new ApiCustomException($message, $errors, $code);
    }

    /**
     * Throws an exception.
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorException(Exception $e)
    {
        throw new HttpException($e->httpStatusCode, $e->getMessage(), $e, $e->getHttpHeaders());
    }

    /**
     * {@inheritdoc}
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ApiValidationException($validator->errors(), $this->getFailedValidationMessage($request));
    }
}
