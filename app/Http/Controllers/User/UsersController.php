<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Exceptions\CustomValidationException;
use App\Exceptions\NotFoundException;
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
            $this->validate($request, User::getValidationRules($request->json()->all()));

            $userID = $request->json('userID');
            $user = User::where('userID', $userID)->with('organization')->first();

            if (!$user) {
                throw new NotFoundException('User not found');
            }

            $user->update($user->getFillableUserDataFromRequest($request));

            $response = $this->createResponseData($user, 'object');
            return response()->json($response);
        } catch (ValidationException $e) {
            return $this->handleError(new CustomValidationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
