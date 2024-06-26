<?php

namespace App\Managers;

use App\Exceptions\UnauthorizedException\UnauthorizedException;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class AuthManager
{

    public function verifyAccess(int $userID, Request $request): void
    {
        $userIDFromToken = $request->userID;
        if ($userID != $userIDFromToken) {
            throw new UnauthorizedException();
        }
    }

    public function createUserJwt(int $userID): string
    {
        $payload = [
            'iss' => "swiftsales-api",
            'iat' => time(),
            'exp' => time() + 60 * 60 * 10,
            'userID' => $userID
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        return $jwt;
    }
}
