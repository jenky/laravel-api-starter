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
     * @param int $id
     * 
     * @return json
     */
    protected function find($resource, $id)
    {
        $data = apihelper($resource)->find($id);

        if (is_null($data)) {
            $this->response->errorNotFound();
        }

        return response()->json($data);
    }
}
