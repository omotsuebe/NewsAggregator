<?php

namespace App\Exceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;

class Handler
{
    public static function configure(Exceptions $exceptions): void
    {
        // Handle validation exceptions
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        });

        // Handle model not found exceptions
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'message' => 'Resource not found.',
            ], 404);
        });

        // Handle HTTP exceptions
        $exceptions->render(function (HttpException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        });

        // Handle generic exceptions
        $exceptions->render(function (\Exception $e, Request $request) {
            Log::error('An error occurred: '.$e->getMessage());

            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        });
    }
}
