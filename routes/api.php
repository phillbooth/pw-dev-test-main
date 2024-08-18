<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductDataController;

// Route to process product data using the controller
Route::post('/api/process-product-data', [ProductDataController::class, 'processProductData']);
