<?php

namespace App\Http\Controllers\SalesAppointment;

use App\Exceptions\CustomValidationException\CustomValidationException;
use App\Exceptions\NotFoundException\NotFoundException;
use App\Http\Controllers\BaseController;
use App\Models\SalesAppointment;
use App\Models\SalesAppointmentFile;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Managers\UploadManager\UploadManager;
use App\Managers\DailyCoManager\DailyCoManager;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class SalesAppointmentsController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'salesAppointments';
        $this->CRUD_RESPONSE_OBJECT = 'salesAppointment';
    }

    public function getAllByUserID(int $userID, Request $request): JsonResponse
    {
        try {
            $this->verifyAccessToResource($userID, $request);
            $salesAppointments = SalesAppointment::with('lead')
                ->where('userID', $userID)
                ->with('salesAppointmentFiles')
                ->get();

            $response = $this->createResponseData($salesAppointments, 'array');
            return response()->json($response);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getPublicSalesAppointment(int $salesAppointmentID): JsonResponse
    {
        try {
            $salesAppointment = SalesAppointment::where('salesAppointmentID', $salesAppointmentID)
                ->with('lead')
                ->with('salesAppointmentFiles')
                ->first();

            if (!$salesAppointment) {
                throw new NotFoundException('SalesAppointment not found');
            }

            $response = $this->createResponseData($salesAppointment, 'object');
            return response()->json($response);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getSingle(int $userID, int $salesAppointmentID, Request $request): JsonResponse
    {
        try {
            $this->verifyAccessToResource($userID, $request);
            $salesAppointment = SalesAppointment::where('salesAppointmentID', $salesAppointmentID)
                ->with('salesAppointmentFiles')
                ->first();

            $response = $this->createResponseData($salesAppointment, 'object');
            return response()->json($response);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            if (!$request->json('leadID')) {
                throw new CustomValidationException('Lead ID is required');
            }
            $lead = Lead::where('leadID', $request->json('leadID'))->first();
            if (!$lead) {
                throw new NotFoundException('Lead not found');
            }

            $userID = $lead->userID;
            $this->verifyAccessToResource($userID, $request);

            DB::beginTransaction();

            $dailyCoManager = new DailyCoManager();
            $meetingUrl = $dailyCoManager->createMeetingUrl(24);
            $salesAppointmentToSave = $request->all();
            $salesAppointmentToSave['meetingUrl'] = $meetingUrl['url'];
            $salesAppointmentToSave['meetingExpiryTime'] = $meetingUrl['expiryTime'];
            $salesAppointment = SalesAppointment::create($salesAppointmentToSave);

            if ($request->json('salesAppointmentFiles')) {
                foreach ($request->json('salesAppointmentFiles') as $salesAppointmentFile) {
                    $base64File = $salesAppointmentFile['base64File'];
                    $fileName = $salesAppointmentFile['fileName'];

                    $uploadManager = new UploadManager();
                    $file = $uploadManager->handleuploadFile($base64File, $fileName);
                    SalesAppointmentFile::create([
                        'fileID' => $file->fileID,
                        'salesAppointmentID' => $salesAppointment->salesAppointmentID,
                    ]);
                }
            }

            DB::commit();
            $response =  $this->createResponseData($salesAppointment, 'object');
            return response()->json($response);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            if (!$request->json('salesAppointmentID')) {
                throw new CustomValidationException('SalesAppointment ID is required');
            }

            $salesAppointmentID = $request->json('salesAppointmentID');
            $salesAppointment = SalesAppointment::where('salesAppointmentID', $salesAppointmentID)->first();

            if (!$salesAppointment) {
                throw new NotFoundException('SalesAppointment not found');
            }

            $this->verifyAccessToResource($salesAppointment->userID, $request);

            DB::beginTransaction();
            $salesAppointment->update($request->except('userID'));

            if ($request->json('salesAppointmentFiles')) {
                foreach ($request->json('salesAppointmentFiles') as $salesAppointmentFile) {
                    $base64File = $salesAppointmentFile['base64File'];
                    $fileName = $salesAppointmentFile['fileName'];

                    $uploadManager = new UploadManager();

                    $file = $uploadManager->handleUploadFile($base64File, $fileName);
                    SalesAppointmentFile::create([
                        'fileID' => $file->fileID,
                        'salesAppointmentID' => $salesAppointment->salesAppointmentID,
                    ]);
                }
            }
            DB::commit();
            $response = $this->createResponseData($salesAppointment, 'object');
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
            if (!$request->json('salesAppointmentID')) {
                throw new CustomValidationException('SalesAppointment ID is required');
            }

            $salesAppointmentID = $request->json('salesAppointmentID');
            $salesAppointment = SalesAppointment::where('salesAppointmentID', $salesAppointmentID)->first();
            if (!$salesAppointment) {
                throw new NotFoundException('SalesAppointment not found');
            }

            $userIDInSalesAppointment = $salesAppointment->userID;
            $this->verifyAccessToResource($userIDInSalesAppointment, $request);
            $salesAppointment->delete();
            return response()->json(['success' => 'SalesAppointment deleted'], 200);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function renewMeetingUrl(int $salesAppointmentID, Request $request): JsonResponse
    {
        try {
            if (!$salesAppointmentID) {
                throw new CustomValidationException('SalesAppointment ID is required');
            }

            $salesAppointmentID = $request->json('salesAppointmentID');
            $salesAppointment = SalesAppointment::where('salesAppointmentID', $salesAppointmentID)->first();
            if (!$salesAppointment) {
                throw new NotFoundException('SalesAppointment not found');
            }

            $userIDInSalesAppointment = $salesAppointment->userID;
            $this->verifyAccessToResource($userIDInSalesAppointment, $request);

            $dailyCoManager = new DailyCoManager();
            $meetingUrl = $dailyCoManager->createMeetingUrl(24);
            $salesAppointment->update([
                'meetingUrl' => $meetingUrl['url'],
                'meetingExpiryTime' => $meetingUrl['expiryTime']
            ]);

            $response = $this->createResponseData($salesAppointment, 'object');
            return response()->json($response);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
