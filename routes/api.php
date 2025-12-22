<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

// Default user route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ============================================
// AUTH ROUTES (PUBLIC)
// ============================================
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

// ============================================
// PRODUCT ROUTES (PUBLIC - GET)
// ============================================
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('products/slug/{slug}', function ($slug) {
    $product = \App\Models\Product::findBySlug($slug);
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }
    return response()->json($product);
});

// ============================================
// PROTECTED ROUTES (AUTHENTICATED USERS)
// ============================================
Route::middleware(['auth:api'])->group(function () {

    // USER: My Transactions (Regular users can see their own transactions)
    Route::get('transactions/my', [TransactionController::class, 'myTransactions']);

    // USER: Create Transaction (Regular users can make purchases)
    Route::post('transactions', [TransactionController::class, 'store']);
});

// ============================================
// ADMIN ONLY ROUTES
// ============================================
Route::middleware(['auth:api', 'admin'])->group(function () {

    // ADMIN: Product Management
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::patch('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // ADMIN: Transaction Management (View all, update, delete)
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{code}', [TransactionController::class, 'show']);
    Route::patch('transactions/{code}', [TransactionController::class, 'update']);
    Route::delete('transactions/{code}', [TransactionController::class, 'destroy']);
});
