<?php

use App\Http\Controllers\ProductDataController;
use Illuminate\Support\Facades\Route;
use ProtoneMedia\LaravelXssProtection\Middleware\XssCleanInput;


// Dummy route to collect the product data for the test
Route::get('product-data', [ProductDataController::class, 'index']);

// Protecting the SKUs route with XSS middleware
Route::middleware(['xss'])->get('/skus', [ProductDataController::class, 'showSkus']);

// 404 Route with XSS protection
Route::fallback(function () {
    return response()->json(['error' => 'Resource not found'], 404);
})->middleware('xss');
