<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function validationError($validator): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
            'data' => []
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function authNotFound($message = ""): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => 'Unauthorised',
            'data' => []
        ], Response::HTTP_UNAUTHORIZED);
    }

    public static function authSuccess($token, $expiresAt = null, $data = []): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged in.',
            'token' => $token,
            'expires_at' => $expiresAt,
            'data' => $data
        ], Response::HTTP_OK);
    }

    public static function success(string $message = '', $data = null, int $httpCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $httpCode);
    }

    public static function error($message = "", int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => []
        ], $httpCode);
    }
}
