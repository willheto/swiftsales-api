<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Exceptions\CustomValidationException\CustomValidationException;
use App\Exceptions\NotFoundException\NotFoundException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;


class UsersController extends BaseController
{
    public function __construct()
    {
        $this->CRUD_RESPONSE_ARRAY = 'users';
        $this->CRUD_RESPONSE_OBJECT = 'user';
    }

    public function update(Request $request)
    {
        try {
            $userID = $request->userID;
            $user = User::where('userID', $userID)->first();
            if (!$user) {
                throw new NotFoundException('User not found');
            }

            $userIDInUser = $user->userID;
            $this->verifyAccessToResource($userIDInUser, $request);
            $user->update($request->except('userID'));
            return $this->createResponseData($user, 'object');
        } catch (ValidationException $e) {
            return $this->handleError(new CustomValidationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
