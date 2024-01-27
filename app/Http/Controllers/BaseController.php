<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as Controller;
use Illuminate\Http\Request;
use App\Managers\AuthManager\AuthManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\ResponseFactory;

class BaseController extends Controller
{
    protected string $CRUD_RESPONSE_ARRAY = "";
    protected string $CRUD_RESPONSE_OBJECT = "";

    protected function createResponseData(mixed $data, string $type): array
    {
        if ($type == "array") {
            return [
                $this->CRUD_RESPONSE_ARRAY => $data
            ];
        }
        if ($type == "object") {
            return [
                $this->CRUD_RESPONSE_OBJECT => $data
            ];
        }

        throw new Exception("Invalid response type");
    }

    protected function handleError(Exception $e): JsonResponse
    {
        $statusCode = ($e->getCode() ? $e->getCode() : 500);
        return response()->json(['error' => $e->getMessage()], $statusCode);
    }

    protected function verifyAccessToResource(int $userID, Request $request): void
    {
        $authManager = new AuthManager();
        $authManager->verifyAccess($userID, $request);
    }
}
