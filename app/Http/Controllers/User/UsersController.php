<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Exceptions\CustomValidationException\CustomValidationException;
use App\Exceptions\NotFoundException\NotFoundException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class UsersController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'users';
        $this->CRUD_RESPONSE_OBJECT = 'user';
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $userID = $request->json('userID');
            $user = User::where('userID', $userID)->with('organization')->first();
            if (!$user) {
                throw new NotFoundException('User not found');
            }

            $userIDInUser = $user->userID;
            $this->verifyAccessToResource($userIDInUser, $request);
            $user->update($request->except('userID'));
            $response = $this->createResponseData($user, 'object');
            return response()->json($response);
        } catch (ValidationException $e) {
            return $this->handleError(new CustomValidationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
