<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceApiController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Attendance Routes
    Route::prefix('attendance')->group(function () {
        Route::post('/submit-location', [AttendanceApiController::class, 'submitLocation']);
        Route::post('/scan-qr', [AttendanceApiController::class, 'scanQR']);
        Route::get('/show-qr', [AttendanceApiController::class, 'showQR']);
    });

    // Profile Routes
    Route::post('/profile/update', [AttendanceApiController::class, 'updateProfile']);
});
