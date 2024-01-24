<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\BaseController;
use App\Models\Lead;
use Exception;
use Illuminate\Http\Request;
use App\Exceptions\NotFoundException\NotFoundException;
use App\Exceptions\CustomValidationException\CustomValidationException;
use Illuminate\Validation\ValidationException;
use App\Managers\ImportManager\ImportManager;

class LeadsController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'leads';
        $this->CRUD_RESPONSE_OBJECT = 'lead';
    }

    public function getAllByUserID($userID, Request $request)
    {
        try {
            $this->verifyAccessToResource($userID, $request);
            $leads = Lead::where('userID', $userID)->get();
            return $this->createResponseData($leads, 'array');
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getSingle($userID, $leadID, Request $request)
    {
        try {
            $this->verifyAccessToResource($userID, $request);
            $lead = Lead::where('userID', $userID)->where('leadID', $leadID)->first();
            if (!$lead) {
                throw new NotFoundException('Lead not found');
            }
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
        } catch (ValidationException $e) {
            return $this->handleError(new CustomValidationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function createBatch(Request $request)
    {
        try {
            $leadsArray = $request->leads;
            $userID = $request->userID;

            $importManager = new ImportManager;
            $leads = $importManager->importLeads($leadsArray, $userID);

            Lead::insert($leads);
            return $this->createResponseData($leads, 'array');
        } catch (ValidationException $e) {
            return $this->handleError(new CustomValidationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $leadID = $request->leadID;
            $lead = Lead::where('leadID', $leadID)->first();
            if (!$lead) {
                throw new NotFoundException('Lead not found', 404);
            }

            $userIDInLead = $lead->userID;
            $this->verifyAccessToResource($userIDInLead, $request);

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
            if (!$request->leadID) {
                throw new CustomValidationException('Lead ID is required');
            }

            $leadID = $request->leadID;
            $lead = Lead::where('leadID', $leadID)->first();
            if (!$lead) {
                throw new NotFoundException('Lead not found', 404);
            }

            $userIDInLead = $lead->userID;
            $this->verifyAccessToResource($userIDInLead, $request);
            $lead->delete();
            return response()->json(['success' => 'Lead deleted'], 200);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
