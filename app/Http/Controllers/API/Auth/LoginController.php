<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\TransientToken;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
                $user = Auth::user();
                $tokenResult = $user->createToken(Str::random(40));
                $token = $tokenResult->plainTextToken;
                $expiresAt = null;
                return ApiResponse::authSuccess($token, $expiresAt, new UserResource($user));
            } else {
                return ApiResponse::authNotFound('Invalid email or password');
            }
        } catch (Exception $e) {
            return ApiResponse::error('Something went wrong!' . $e->getMessage());
        }
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $token = $user->currentAccessToken();
        if ($token && !$token instanceof TransientToken) {
            $token->delete();
        }

        return ApiResponse::success('Successfully logged out.', $data = null);
    }

    public function authCheck(): JsonResponse
    {
        if (Auth::check()) {
            return ApiResponse::success('Authorized', []);
        }
        return ApiResponse::error('Unauthorized', 401);
    }
}
