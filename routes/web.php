<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| Hayo Chicken - Web Routes
|--------------------------------------------------------------------------
*/

// Halaman splash / welcome
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::get('/masuk',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/masuk',  [AuthController::class, 'login'])->name('login.post');

Route::get('/daftar',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');

Route::get('/lupa-password',  [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.reset.post');

Route::get('/ubah-password',  [AuthController::class, 'showChangePassword'])->name('password.change');
Route::post('/ubah-password', [AuthController::class, 'changePassword'])->name('password.change.post');

Route::match(['GET', 'POST'], '/keluar', [AuthController::class, 'logout'])->name('logout');

// ─── Protected Routes (Web) ──────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::post('/profil/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // ─── Buyer ──────────────────────────────────────────────────────────────
    Route::get('/beranda',      [BuyerController::class, 'home'])->name('home');
    Route::get('/produk/{id?}', [BuyerController::class, 'productDetail'])->name('product.detail');
    Route::get('/keranjang',    [BuyerController::class, 'cart'])->name('cart');
    Route::get('/checkout',     [BuyerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',    [OrderController::class, 'checkout'])->name('order.checkout');

    Route::get('/pesanan/berhasil/{order}', [BuyerController::class, 'orderSuccess'])->name('order.success');
    Route::get('/pesanan/status/{order}',   [BuyerController::class, 'orderStatus'])->name('order.status');
    Route::get('/pesanan/riwayat',  [BuyerController::class, 'orderHistory'])->name('order.history');
    Route::get('/pesanan/aktif',    [BuyerController::class, 'orderActive'])->name('order.active');

    Route::get('/notifikasi',       [BuyerController::class, 'notifications'])->name('notifications');

    Route::get('/alamat',       [BuyerController::class, 'savedAddresses'])->name('address.saved');
    Route::get('/alamat/tambah',[BuyerController::class, 'addAddress'])->name('address.add');

    // ─── Cart (Web Session Route) ────────────────────────────────────────────
    Route::get('/web/cart',                 [CartController::class, 'index'])->name('web.cart.index');
    Route::post('/web/cart',                [CartController::class, 'store'])->name('web.cart.store');
    Route::patch('/web/cart/{cartItem}',    [CartController::class, 'update'])->name('web.cart.update');
    Route::delete('/web/cart/{cartItem}',   [CartController::class, 'destroy'])->name('web.cart.destroy');
    Route::patch('/web/cart/{cartItem}/toggle-check', [CartController::class, 'toggleCheck'])->name('web.cart.toggle');

    // ─── Favorites (Web Session Route) ───────────────────────────────────────
    Route::get('/web/favorites',            [FavoriteController::class, 'index'])->name('web.fav.index');
    Route::post('/web/favorites/toggle',    [FavoriteController::class, 'toggle'])->name('web.fav.toggle');

    // ─── Orders (Web Session Route) ──────────────────────────────────────────
    Route::get('/web/orders',               [OrderController::class, 'index'])->name('web.orders.index');
});

// ─── Seller (Protected) ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('order.update-status');
});
