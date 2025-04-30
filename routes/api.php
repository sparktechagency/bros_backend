<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth routes
Route::prefix('auth')->group(function () {

});

// admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

});

// user routes
Route::middleware(['auth:sanctum', 'user'])->group(function () {

});
