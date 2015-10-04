<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;

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
    protected function responseFind($resource, $id)
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
    protected function responseUpdate($resource, $id, array $data)
    {
        $resource = $resource->find($id);

        if (is_null($resource)) {
            $this->response->errorNotFound();
        }

        $resource->update($data);

        return response()->json($resource);
    }
}
