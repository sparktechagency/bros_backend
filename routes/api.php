<?php

use App\Http\Controllers\api\Backend\AuthController;
use App\Http\Controllers\api\Backend\DashboardController;
use App\Http\Controllers\api\Backend\ManageDateController;
use App\Http\Controllers\api\Backend\PageController;
use App\Http\Controllers\api\Backend\PhotoGalleryController;
use App\Http\Controllers\api\Backend\ServiceController;
use App\Http\Controllers\api\Backend\ServiceTimeController;
use App\Http\Controllers\api\Backend\TransactionController;
use App\Http\Controllers\api\Backend\UserController;
use App\Http\Controllers\api\Frontend\BookingController;
use App\Http\Controllers\api\Frontend\CarImageController;
use App\Http\Controllers\api\Frontend\FeedbackController;
use App\Http\Controllers\api\Frontend\HomeController;
use App\Http\Controllers\api\Frontend\StripePaymentController;
use App\Http\Controllers\api\Frontend\SupportMessageController;
use App\Http\Controllers\NotificationController;
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
        Route::get('check-token', [AuthController::class, 'validateToken'])->name('validateToken');
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('change-profile', [AuthController::class, 'changeProfile']);
        Route::post('change-profile-photo', [AuthController::class, 'changeProfilePhoto']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::resource('pages', PageController::class)->only(['index', 'store']);
    Route::resource('manage-dates', ManageDateController::class)->only(['index', 'store', 'destroy']);
    Route::resource('photo-gallery', PhotoGalleryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('service_times', ServiceTimeController::class);
    Route::resource('users', UserController::class)->only('index', 'destroy', 'show');
    Route::resource('bookings', BookingController::class)->except('store', 'create', 'show');
    Route::get('booking-status/{id}', [BookingController::class, 'bookingStatus']);
    Route::resource('transactions', TransactionController::class)->only('index');
    Route::get('dashboard', DashboardController::class);
    Route::resource('feedbacks', FeedbackController::class)->only('index', 'destroy');
    Route::get('feedback-highlight/{id}', [FeedbackController::class, 'feedbackHighlight']);
});

// user routes
Route::middleware(['auth:sanctum', 'user'])->group(function () {
    Route::resource('car-photo', CarImageController::class);
    Route::resource('manage-dates', ManageDateController::class)->only(['index']);


    Route::resource('bookings', BookingController::class)->only('store', 'index', 'show');
    Route::resource('feedbacks', FeedbackController::class)->only('store');
    // Route::get('home', [HomeController::class, 'home']);
    Route::get('get_free_times', [BookingController::class, 'getFreeTimes']);

    // stripe
    Route::post('booking-intent', [StripePaymentController::class, 'bookingIntent']);
});
// common routes
Route::middleware(['auth:sanctum', 'admin.user'])->group(function () {
    Route::resource('bookings', BookingController::class)->only('show');
    Route::get('notifications', [NotificationController::class, 'notifications'])->name('all_Notification');
    Route::post('mark-notification/{id}', [NotificationController::class, 'singleMark'])->name('singleMark');
    Route::post('mark-all-notification', [NotificationController::class, 'allMark'])->name('allMark');
});

Route::get('feedback', [HomeController::class, 'feedback']);
Route::get('home', [HomeController::class, 'home']);
Route::resource('pages', PageController::class)->only('index');
Route::post('support-message', [SupportMessageController::class, 'supportMessage']);
Route::resource('photo-gallery', PhotoGalleryController::class)->only('index');
 Route::resource('services', ServiceController::class)->only('index', 'show');
