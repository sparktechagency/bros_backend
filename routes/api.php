<?php

use App\Http\Controllers\api\Backend\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth routes
Route::prefix('auth')->group(function () {
    Route::post('social-login', [AuthController::class, 'socialLogin']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('check-otp', [AuthController::class, 'checkOtp']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('change-profile', [AuthController::class, 'changeProfile']);
    });
});

// admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

});

// user routes
Route::middleware(['auth:sanctum', 'user'])->group(function () {

});
