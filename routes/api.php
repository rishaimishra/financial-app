<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Business routes
    Route::apiResource('businesses', \App\Http\Controllers\API\BusinessController::class);
    Route::get('/businesses/{id}/summary', [\App\Http\Controllers\API\BusinessController::class, 'summary']);
    Route::get('/businesses-summaries/all', [\App\Http\Controllers\API\BusinessController::class, 'allSummaries']);

    // Add your other protected routes here
});
