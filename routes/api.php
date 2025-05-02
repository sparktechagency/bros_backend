<?php

use App\Http\Controllers\api\Backend\AuthController;
use App\Http\Controllers\api\Backend\ManageDateController;
use App\Http\Controllers\api\Backend\PageController;
use App\Http\Controllers\api\Backend\PhotoGalleryController;
use App\Http\Controllers\api\Backend\ServiceController;
use App\Http\Controllers\api\Frontend\CarImageController;
use App\Http\Controllers\api\Frontend\SupportMessageController;
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
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::resource('pages', PageController::class)->only(['index', 'store']);
    Route::resource('manage-dates', ManageDateController::class)->only(['index', 'store', 'destroy']);
    Route::resource('photo-gallery', PhotoGalleryController::class);
    Route::resource('services', ServiceController::class);
});

// user routes
Route::middleware(['auth:sanctum', 'user'])->group(function () {
    Route::resource('pages', PageController::class)->only('index');
    Route::resource('car-photo', CarImageController::class);
    Route::resource('manage-dates', ManageDateController::class)->only(['index']);
    Route::post('support-message', [SupportMessageController::class, 'supportMessage']);
    Route::resource('photo-gallery', PhotoGalleryController::class)->only('index');
    Route::resource('services', ServiceController::class)->only('index','show');
});
