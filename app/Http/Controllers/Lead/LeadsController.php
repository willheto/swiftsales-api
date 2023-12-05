<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\BaseController;
use App\Models\Lead;
use Exception;

class LeadsController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'leads';
    }

    public function getAllByUserID($userID)
    {
        try {
            $leads = Lead::where('userID', $userID)->get();
            return $this->createResponseData($leads);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }
}
