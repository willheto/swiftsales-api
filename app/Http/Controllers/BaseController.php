<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as Controller;
use Illuminate\Http\Request;
use App\Managers\AuthManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    const RESOURCE_MODEL = '';

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

    const ERROR_CODES_TO_LOG = [401, 403, 422, 500];

    private function isErrorSuspicous(int $statusCode): bool
    {
        return in_array($statusCode, self::ERROR_CODES_TO_LOG) && env('APP_ENV') === 'production';
    }

    protected function handleError(Exception $e): JsonResponse
    {
        $malformedToken = str_contains($e->getMessage(), 'malformed') || str_contains($e->getMessage(), 'Unexpected control character found') || str_contains($e->getMessage(), 'Signature verification failed');
        $exceptionIsExpiredToken = $e->getMessage() === 'Expired token';

        if ($exceptionIsExpiredToken) {
            return response()->json(['error' => 'Expired token'], 401);
        }

        if ($malformedToken) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Check for invalid HTTP status code error
        if (str_contains($e->getMessage(), 'The HTTP status code')) {
            return response()->json(['error' => 'Invalid HTTP status code'], 500);
        }

        // Validate the status code, default to 500 if invalid
        $statusCode = ($e->getCode() && $this->isValidHttpStatusCode($e->getCode())) ? $e->getCode() : 500;

        if ($this->isErrorSuspicous($statusCode)) {
            $this->logSuspiciousActivityToTelegram($e);
        }

        return response()->json(['error' => $e->getMessage()], $statusCode);
    }

    /**
     * Validates if a given status code is within the valid HTTP status code range.
     *
     * @param int $statusCode
     * @return bool
     */
    private function isValidHttpStatusCode($statusCode): bool
    {
        return is_numeric($statusCode) && $statusCode >= 100 && $statusCode <= 599;
    }


    protected function logSuspiciousActivityToTelegram(Exception $exception): void
    {
        $statusCode = ($exception->getCode() ? $exception->getCode() : 500);
        Log::channel('telegram')->debug('https://http.cat/' . $statusCode . '.jpg ' . $exception);
    }

    protected function verifyAccessToResource(int $userID, Request $request): void
    {
        $authManager = new AuthManager();
        $authManager->verifyAccess($userID, $request);
    }
}
