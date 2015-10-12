<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidationException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use Helpers;

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
        throw new ApiValidationException($validator->errors());        
    }
}
