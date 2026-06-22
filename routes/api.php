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

    // --- AUTHENTICATION ENDPOINTS (SDD Lapis 1: Max 20 req/menit per IP) ---
    Route::prefix('auth')->middleware('throttle:20,1')->group(function () {
        // Public
        Route::post('register',       [AuthController::class, 'register']);
        Route::post('login',          [AuthController::class, 'login']);
        Route::post('refresh',        [AuthController::class, 'refresh']);
        Route::post('forgot-password',[AuthController::class, 'sendResetLink']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        
        // Rute Edit Profil (Manual Auth di Controller)
        Route::patch('profile', [AuthController::class, 'updateProfile']);
        Route::patch('password', [AuthController::class, 'updatePassword']);

        Route::middleware('auth:api,web')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    // --- MAIN API ENDPOINTS ---
    Route::get('products',       [ProductController::class, 'index']);
    Route::get('products/{id}',  [ProductController::class, 'show']);
    Route::get('categories',     [CategoryController::class, 'index']);
    Route::middleware('auth:api,web')->group(function () {

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
