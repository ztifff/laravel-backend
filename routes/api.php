<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Authentication (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Product Catalog (public)
Route::get('/products',        [ProductController::class, 'index']);
Route::get('/products/{id}',   [ProductController::class, 'show']);
Route::get('/categories',      [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // User account
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    /*
    |--------------------------------------------------------------------------
    | CART ROUTES (User only)
    |--------------------------------------------------------------------------
    */
    Route::get('/cart',                 [CartController::class, 'index']);
    Route::post('/cart',                [CartController::class, 'store']);
    Route::put('/cart/{productId}',     [CartController::class, 'update']);
    Route::delete('/cart/{productId}',  [CartController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | ORDER ROUTES (User can create + view only their orders)
    |--------------------------------------------------------------------------
    */
    Route::post('/orders', [OrderController::class, 'store']);      // place order
    Route::get('/orders',  [OrderController::class, 'index']);      // list user's orders
    Route::get('/orders/{id}', [OrderController::class, 'show']);   // view specific order

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {

        // Admin dashboard
        Route::get('/admin/dashboard', function () {
            return "Welcome Admin!";
        });

        Route::middleware('auth:sanctum')->prefix('admin')->group(function() {
            Route::get('/products', [ProductController::class, 'index']);
            Route::get('/products/{id}', [ProductController::class, 'show']);
            Route::post('/products', [ProductController::class, 'store']);
            Route::put('/products/{id}', [ProductController::class, 'update']);
            Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        });

        // Admin category management
        Route::apiResource('/admin/categories', CategoryController::class);

        // Admin order management
            Route::get('/admin/orders', [OrderController::class, 'adminIndex']); // list all orders
            Route::get('/admin/orders/{id}', [OrderController::class, 'adminShow']);  // view specific order
            Route::middleware(['auth:sanctum', 'admin'])->group(function () {
                Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);
            });

    });
});
