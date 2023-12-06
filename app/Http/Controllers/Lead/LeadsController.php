<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\BaseController;
use App\Models\Lead;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class LeadsController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'leads';
        $this->CRUD_RESPONSE_OBJECT = 'lead';
    }



    public function getAllByUserID($userID)
    {
        try {
            $leads = Lead::where('userID', $userID)->get();
            return $this->createResponseData($leads, 'array');
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getSingle($userID, $leadID)
    {
        try {
            $lead = Lead::where('userID', $userID)->where('leadID', $leadID)->first();
            return $this->createResponseData($lead, 'object');
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function create(Request $request)
    {
        try {
            $this->validate($request, Lead::getValidationRules());
            $lead = Lead::create($request->all());
            return $this->createResponseData($lead, 'object');
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $leadID = $request->leadID;
            $userID = $request->userID;

            if (!$leadID) {
                return response()->json(['error' => 'Lead ID is required'], 400);
            }
            $lead = Lead::where('leadID', $leadID)->where('userID', $userID)->first();
            if (!$lead) {
                return response()->json(['error' => 'Lead not found'], 404);
            }

            $lead->update($request->except('userID'));
            return $this->createResponseData($lead, 'object');
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deleteSingle(Request $request)
    {
        try {
            $leadID = $request->leadID;
            $userID = $request->userID;

            $lead = Lead::where('leadID', $leadID)->where('userID', $userID)->first();
            if (!$lead) {
                return response()->json(['error' => 'Lead not found'], 404);
            }
            $lead->delete();
            return response()->json(['success' => 'Lead deleted'], 200);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
