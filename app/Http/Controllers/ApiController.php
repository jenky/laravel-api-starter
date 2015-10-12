<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidationException;
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
     * Find resource by id using api helper.
     * 
     * @param mixed $resource
     * @param int   $id
     * 
     * @return json
     */
    protected function findResource($resource, $id)
    {
        $data = apihelper($resource)->find($id);

        if (is_null($data)) {
            $this->response->errorNotFound();
        }

        return response()->json($data);
    }

    /**
     * Update resource by id.
     * 
     * @param mixed $resource
     * @param int $id
     * @param array $data
     * 
     * @return json
     */
    protected function updateResource($resource, $id, array $data)
    {
        $resource = $resource->find($id);

        if (is_null($resource)) {
            $this->response->errorNotFound();
        }

        $resource->update($data);

        return response()->json($resource);
    }

    /**
     * Delete resource by id.
     * 
     * @param mixed $resource
     * @param mixed $id
     * 
     * @return json
     */
    protected function deleteResource($resource, $id)
    {
        $resource->destroy($id);

        return response()->json('', 204);
    }

    /**
     * {@inheritdoc}
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ApiValidationException($validator->errors(), $this->buildFailedValidationMessage($request));
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
        $method = strtoupper($request->method());

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