<?php

namespace App\Http\Controllers\SalesAppointment;

use App\Http\Controllers\BaseController;
use App\Models\SalesAppointment;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class SalesAppointmentsController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'salesAppointments';
        $this->CRUD_RESPONSE_OBJECT = 'salesAppointment';
    }

    public function getAllByUserID($userID)
    {
        try {
            $salesAppointments = SalesAppointment::with('lead')->where('userID', $userID)->get();
            return $this->createResponseData($salesAppointments, 'array');
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getSingle($userID, $salesAppointmentID)
    {
        try {
            $salesAppointment = SalesAppointment::where('userID', $userID)->where('salesAppointmentID', $salesAppointmentID)->first();
            return $this->createResponseData($salesAppointment, 'object');
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function create(Request $request)
    {
        try {
            $this->validate($request, SalesAppointment::getValidationRules());
            $salesAppointment = SalesAppointment::create($request->all());
            return $this->createResponseData($salesAppointment, 'object');
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $salesAppointmentID = $request->salesAppointmentID;
            $userID = $request->userID;

            if (!$salesAppointmentID) {
                return response()->json(['error' => 'SalesAppointment ID is required'], 400);
            }
            $salesAppointment = SalesAppointment::where('salesAppointmentID', $salesAppointmentID)->where('userID', $userID)->first();
            if (!$salesAppointment) {
                return response()->json(['error' => 'SalesAppointment not found'], 404);
            }

            $salesAppointment->update($request->except('userID'));
            return $this->createResponseData($salesAppointment, 'object');
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deleteSingle(Request $request)
    {
        try {
            $salesAppointmentID = $request->salesAppointmentID;
            $userID = $request->userID;

            $salesAppointment = SalesAppointment::where('salesAppointmentID', $salesAppointmentID)->where('userID', $userID)->first();
            if (!$salesAppointment) {
                return response()->json(['error' => 'SalesAppointment not found'], 404);
            }
            $salesAppointment->delete();
            return response()->json(['success' => 'SalesAppointment deleted'], 200);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
