<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login_with_token', [AuthController::class, 'loginWithToken'])
        ->middleware('auth:sanctum')
        ->name('login_with_token');
    Route::get('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
});