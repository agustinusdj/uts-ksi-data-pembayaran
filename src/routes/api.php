<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaymentGatewayController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\PaymentTransactionController;
use App\Http\Controllers\API\PaymentAnalyticController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Payment Transactions - Accessible to authenticated users (create only)
    Route::post('/transactions', [PaymentTransactionController::class, 'store']);
    Route::get('/transactions/{id}', [PaymentTransactionController::class, 'show']);
    
    // Payment Methods - Public read access for methods
    Route::get('/methods', [PaymentMethodController::class, 'index']);
    Route::get('/methods/{id}', [PaymentMethodController::class, 'show']);
    
    // Admin only routes
    Route::middleware('role:admin|super_admin')->group(function () {
        // Payment Transactions - Admin access
        Route::get('/transactions', [PaymentTransactionController::class, 'index']);
        Route::put('/transactions/{id}', [PaymentTransactionController::class, 'update']);
        
        // Payment Analytics - Admin access
        Route::get('/analytics', [PaymentAnalyticController::class, 'index']);
        Route::get('/analytics/{id}', [PaymentAnalyticController::class, 'show']);
    });
    
    // Super Admin only routes
    Route::middleware('role:super_admin')->group(function () {
        // Payment Gateways - Super Admin access
        Route::apiResource('/gateways', PaymentGatewayController::class);
        
        // Payment Methods - Super Admin write access
        Route::post('/methods', [PaymentMethodController::class, 'store']);
        Route::put('/methods/{id}', [PaymentMethodController::class, 'update']);
        Route::delete('/methods/{id}', [PaymentMethodController::class, 'destroy']);
        
        // Payment Transactions - Super Admin delete access
        Route::delete('/transactions/{id}', [PaymentTransactionController::class, 'destroy']);
        
        // Payment Analytics - Super Admin write access
        Route::post('/analytics', [PaymentAnalyticController::class, 'store']);
        Route::put('/analytics/{id}', [PaymentAnalyticController::class, 'update']);
        Route::delete('/analytics/{id}', [PaymentAnalyticController::class, 'destroy']);
    });
});
