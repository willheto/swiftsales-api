<?php

namespace App\Managers\AuthManager;

use App\Exceptions\UnauthorizedException\UnauthorizedException;
use Illuminate\Http\Request;

class AuthManager
{

    public function verifyAccess(int $userID, Request $request): void
    {
        $userIDFromToken = $request->userID;
        if ($userID != $userIDFromToken) {
            throw new UnauthorizedException();
        }
    }
}
