<?php

namespace App\Http\Controllers\User;

use App\Exceptions\CustomValidationException;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Managers\AuthManager;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;


class UserAuthController extends BaseController
{

    public function authenticate(Request $request): JsonResponse
    {
        try {
            $token = $request->json('token');
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $userID = $decoded->userID;
            $user = User::where('userID', $userID)->with('organization')->first();

            if (empty($user)) {
                throw new Exception('User not found', 404);
            }

            return response()->json(['user' => $user->toArray()]);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'email' => ['email', 'required'],
                'password' => 'required'
            ]);

            $email = $request->json('email');
            $user = User::where('email', $email)->with('organization')->first();

            $password = $request->json('password');
            if (empty($user) || Hash::check($password, $user->password) === false) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $authManager = new AuthManager();
            $jwt = $authManager->createUserJwt($user->userID);

            $response = [
                'user' => $user->toArray(),
                'token' => $jwt
            ];

            return response()->json($response);
        } catch (ValidationException $e) {
            $exceptionMessage = $e->getMessage();
            $validationException = new CustomValidationException($exceptionMessage);
            return $this->handleError($validationException);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function checkPassword(Request $request): JsonResponse
    {
        try {
            $userID = $request->json('userID');
            $this->verifyAccessToResource($userID, $request);

            $password = $request->json('password');
            $user = User::where('userID', $userID)->first();

            if (!$user) {
                throw new NotFoundException('User not found');
            }

            if (!password_verify($password, $user->password)) {
                throw new BadRequestException('Password is incorrect', 400);
            }

            return response()->json(['isValid' => true]);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
