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
    Route::post('/business/expense', [\App\Http\Controllers\API\BusinessApiController::class, 'createExpense']);
    Route::post('/business/income', [\App\Http\Controllers\API\BusinessApiController::class, 'createIncome']);

    // Financial Goals routes
    Route::apiResource('financial-goals', \App\Http\Controllers\API\FinancialGoalController::class);
    Route::post('/financial-goals/{id}/contributions', [\App\Http\Controllers\API\FinancialGoalController::class, 'addContribution']);
    Route::delete('/financial-goals/{goalId}/contributions/{contributionId}', [\App\Http\Controllers\API\FinancialGoalController::class, 'removeContribution']);
    Route::post('/financial-goals/{id}/toggle-completion', [\App\Http\Controllers\API\FinancialGoalController::class, 'toggleCompletion']);
    Route::get('/financial-goals-summary', [\App\Http\Controllers\API\FinancialGoalController::class, 'getSummary']);

    // Add your other protected routes here
});
