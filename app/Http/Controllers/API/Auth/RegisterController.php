<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $token = $user->createToken('api-token')->plainTextToken;
            return ApiResponse::authSuccess($token, $expiresAt = null, new UserResource($user), 201);
        } catch (Exception $e) {
            return ApiResponse::error('Something went wrong!');
        }
    }

}
