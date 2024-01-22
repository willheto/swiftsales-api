<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as Controller;
use Illuminate\Http\Request;
use App\Managers\AuthManager\AuthManager;

class BaseController extends Controller
{
    protected $CRUD_RESPONSE_ARRAY = "";
    protected $CRUD_RESPONSE_OBJECT = "";

    protected function createResponseData($data, $type)
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
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : ($e->getCode() ? $e->getCode() : 500);
        return response()->json(['error' => $e->getMessage()], $statusCode);
    }

    protected function verifyAccessToResource($userID, Request $request)
    {
        $authManager = new AuthManager();
        $authManager->verifyAccess($userID, $request);
    }
}
