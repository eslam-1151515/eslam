<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\OrderController as ApiOrderController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\CustomerController;

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

// Orders API endpoint (no authentication required) - للواجهة الأمامية
Route::post('/orders', [OrderController::class, 'storeApi'])
    ->middleware([\App\Http\Middleware\IdentifyTenant::class])
    ->name('api.orders.store');

// Health check endpoint (no authentication required)
use App\Http\Controllers\Api\HealthCheckController;
Route::get('/health', [HealthCheckController::class, 'check'])->name('api.health');

// ============================================================
// API v1 - يتطلب مفتاح API في Authorization header
// ============================================================
Route::prefix('v1')->middleware(['auth.apikey', 'throttle:api'])->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', ApiOrderController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('customers', CustomerController::class);
});