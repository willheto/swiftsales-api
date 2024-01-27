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
use Illuminate\Http\JsonResponse;

class LeadsController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'leads';
        $this->CRUD_RESPONSE_OBJECT = 'lead';
    }

    public function getAllByUserID(int $userID, Request $request): JsonResponse
    {
        try {
            $this->verifyAccessToResource($userID, $request);
            $leads = Lead::where('userID', $userID)->get();
            $response = $this->createResponseData($leads, 'array');
            return response()->json($response);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getSingle(int $userID, int $leadID, Request $request): JsonResponse
    {
        try {
            $this->verifyAccessToResource($userID, $request);
            $lead = Lead::where('userID', $userID)->where('leadID', $leadID)->first();
            if (!$lead) {
                throw new NotFoundException('Lead not found');
            }
            $response = $this->createResponseData($lead, 'object');
            return response()->json($response);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $this->validate($request, Lead::getValidationRules());
            $lead = Lead::create($request->all());
            $response = $this->createResponseData($lead, 'object');
            return response()->json($response);
        } catch (ValidationException $e) {
            return $this->handleError(new CustomValidationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function createBatch(Request $request): JsonResponse
    {
        try {
            $leadsArray = $request->json('leads');
            $userID = $request->json('userID');
            $importManager = new ImportManager;
            $leads = $importManager->importLeads($leadsArray, $userID);

            Lead::insert($leads);
            $response = $this->createResponseData($leads, 'array');
            return response()->json($response);
        } catch (ValidationException $e) {
            return $this->handleError(new CustomValidationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $leadID = $request->json('leadID');
            $lead = Lead::where('leadID', $leadID)->first();
            if (!$lead) {
                throw new NotFoundException('Lead not found', 404);
            }

            $userIDInLead = $lead->userID;
            $this->verifyAccessToResource($userIDInLead, $request);

            $lead->update($request->except('userID'));
            $response = $this->createResponseData($lead, 'object');
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deleteSingle(Request $request): JsonResponse
    {
        try {
            if (!$request->json('leadID')) {
                throw new CustomValidationException('Lead ID is required');
            }

            $leadID = $request->json('leadID');
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
