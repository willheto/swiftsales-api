<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as Controller;

class BaseController extends Controller
{
    protected $CRUD_RESPONSE_ARRAY = "";
    protected $CRUD_RESPONSE_OBJECT = "";

    public function createResponseData($data, $type)
    {
        if ($type == "array")
            return response([
                $this->CRUD_RESPONSE_ARRAY => $data
            ], 200);
        else if ($type == "object")
            return response([
                $this->CRUD_RESPONSE_OBJECT => $data
            ], 200);
    }

    protected function handleError($e)
    {
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        return response()->json(['error' => $e->getMessage()], $statusCode);
    }
}
