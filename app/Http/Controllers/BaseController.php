<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as Controller;

class BaseController extends Controller
{
    protected $CRUD_RESPONSE_ARRAY = "";
    public function createResponseData($data)
    {
        return response([
            $this->CRUD_RESPONSE_ARRAY => $data
        ], 200);
    }
}
