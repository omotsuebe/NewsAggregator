<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class BaseApiController extends BaseController
{
    protected function jsonSuccessWithData(array $data, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'result' => true,
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function jsonError(string $message, int $statusCode = 500, array $errors = []): JsonResponse
    {
        return response()->json([
            'result' => false,
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
