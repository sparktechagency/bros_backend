<?php

use App\Http\Controllers\api\Backend\AuthController;
use App\Http\Controllers\api\Backend\DashboardController;
use App\Http\Controllers\api\Backend\ManageDateController;
use App\Http\Controllers\api\Backend\PageController;
use App\Http\Controllers\api\Backend\PhotoGalleryController;
use App\Http\Controllers\api\Backend\ServiceController;
use App\Http\Controllers\api\Backend\TransactionController;
use App\Http\Controllers\api\Frontend\BookingController;
use App\Http\Controllers\api\Frontend\CarImageController;
use App\Http\Controllers\api\Frontend\FeedbackController;
use App\Http\Controllers\api\Frontend\HomeController;
use App\Http\Controllers\api\Frontend\StripePaymentController;
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
    Route::resource('bookings', BookingController::class)->except('store','create');
    Route::get('booking-status/{id}', [BookingController::class, 'bookingStatus']);
    Route::resource('transactions', TransactionController::class)->only('index');
    Route::get('dashboard',DashboardController::class);
    Route::resource('feedbacks', FeedbackController::class)->only('index','destroy');
    Route::get('feedback-highlight/{id}', [FeedbackController::class, 'feedbackHighlight']);
});

// user routes
Route::middleware(['auth:sanctum', 'user'])->group(function () {
    Route::resource('pages', PageController::class)->only('index');
    Route::resource('car-photo', CarImageController::class);
    Route::resource('manage-dates', ManageDateController::class)->only(['index']);
    Route::post('support-message', [SupportMessageController::class, 'supportMessage']);
    Route::resource('photo-gallery', PhotoGalleryController::class)->only('index');
    Route::resource('services', ServiceController::class)->only('index', 'show');
    Route::resource('bookings', BookingController::class)->only('store','index');
    Route::resource('feedbacks', FeedbackController::class)->only('store');
    Route::get('home',[HomeController::class,'home']);
    Route::get('feedback',[HomeController::class,'feedback']);

    // stripe
    Route::post('booking-intent', [StripePaymentController::class, 'bookingIntent']);
});
