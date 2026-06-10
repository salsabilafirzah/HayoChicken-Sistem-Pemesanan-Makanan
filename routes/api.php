<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public
    Route::post('auth/register',       [AuthController::class, 'register']);
    Route::post('auth/login',          [AuthController::class, 'login']);
    Route::post('auth/refresh',        [AuthController::class, 'refresh']);
    Route::post('auth/forgot-password',[AuthController::class, 'sendResetLink']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);
    
    Route::get('products',       [ProductController::class, 'index']);
    Route::get('products/{id}',  [ProductController::class, 'show']);
    Route::get('categories',     [CategoryController::class, 'index']);

    Route::middleware('auth:api,web')->group(function () {

        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Keranjang
        Route::get('cart',                          [CartController::class, 'index']);
        Route::post('cart',                         [CartController::class, 'store']);
        Route::put('cart/{cartItem}',               [CartController::class, 'update']);
        Route::delete('cart/{cartItem}',            [CartController::class, 'destroy']);
        Route::patch('cart/{cartItem}/toggle-check',[CartController::class, 'toggleCheck']);

        // Favorit
        Route::get('favorites',        [FavoriteController::class, 'index']);
        Route::post('favorites/toggle',[FavoriteController::class, 'toggle']);

        // Checkout & Pesanan (Customer)
        Route::post('orders',                  [OrderController::class, 'checkout']);
        Route::get('orders',                   [OrderController::class, 'index']);
        Route::get('orders/{order}',           [OrderController::class, 'show']);

        // Seller Only
        Route::middleware('role:seller')->prefix('seller')->group(function () {
            Route::get('orders',                   [OrderController::class, 'allOrders']);
            Route::patch('orders/{order}/status',  [OrderController::class, 'updateStatus']);
            Route::get('analytics/summary',        [AnalyticsController::class, 'summary']);
            Route::apiResource('products', ProductController::class)->except(['index','show']);
            Route::patch('products/{product}/toggle', [ProductController::class, 'toggleAvailability']);
        });
    });
});
