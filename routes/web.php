<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\ScanQRController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Employee\ScanQRController as EmployeeScanQRController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/sso', [AuthController::class, 'ssoLogin'])->name('auth.sso');
Route::get('/auth/ssoCallback', [AuthController::class, 'ssoCallback'])->name('auth.ssoCallback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('sessions', SessionController::class);
        Route::get('scan-qr', [ScanQRController::class, 'index'])->name('scan-qr');
        Route::get('show-qr/{sessionId?}', [ScanQRController::class, 'showQR'])->name('show-qr');
        Route::get('reports', [ReportController::class, 'index'])->name('reports');
    });

    // Employee Routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('scan-qr', [EmployeeScanQRController::class, 'index'])->name('scan-qr');
    });



    // Presence Methods
    Route::post('/presence/submit-location', [AttendanceController::class, 'submitLocation'])->name('presence.submitLocation');
    // Employee scan admin QR
    Route::get('/presence/scan-qr/{token}', [AttendanceController::class, 'scanQRByEmployee'])->name('presence.scanQR');
    // Admin scan employee QR
    Route::post('/presence/process-qr', [AttendanceController::class, 'adminScanUserQR'])->name('presence.processQR');
});
