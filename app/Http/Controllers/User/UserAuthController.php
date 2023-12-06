<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserAuthController extends BaseController
{

    public function authenticate(Request $request)
    {
        try {
            $token = $request->token;
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $userID = $decoded->userID;
            $user = User::where('userID', $userID)->first();

            if (empty($user)) {
                throw new Exception('User not found', 404);
            }

            return response()->json(['user' => $user->toArray()]);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => ['email', 'required'],
                'password' => 'required'
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            $password = $request->password;
            if (empty($user) || Hash::check($password, $user->password) === false) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $payload = [
                'iss' => "swiftsales-api",
                'iat' => time(),
                'exp' => time() + 60 * 60 * 10,
                'userID' => $user->userID
            ];

            $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
            return response()->json(['token' => $jwt, 'user' => $user->toArray()]);
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
}
