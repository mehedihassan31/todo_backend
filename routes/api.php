<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);
    Route::apiResource('tasks', TaskController::class);
    Route::post('logout', [LoginController::class, 'logout']);
});
