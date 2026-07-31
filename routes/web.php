<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Maincontroller;
use App\Http\Controllers\AdminController;

// Home
Route::get('/', [Maincontroller::class, 'home'])->name('home');

// Products page
Route::get('/products', [Maincontroller::class, 'productsPage'])->name('products');


// FILTER (IMPORTANT: before any dynamic route)
Route::get('/products/filter', [Maincontroller::class, 'search'])->name('search');

// Cart
Route::get('/cart', [CartController::class, 'cart'])->name('cart');

Route::post('/addToCart/{id}', [CartController::class, 'addToCart']);
Route::post('/remove/{id}', [CartController::class, 'removeFromCart']);
Route::post('/changeQuan/{id}', [CartController::class, 'changeQuantity']);

// Checkout
Route::get('/checkout', [Maincontroller::class, 'checkout'])->name('checkout');
Route::post('/checkout', [Maincontroller::class, 'placeOrder'])->name('placeOrder');

// Auth
Route::get('/register', [AuthController::class, 'registerPage'])->middleware('guest')->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

Route::get('/login', [AuthController::class, 'loginPage'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ===== ADMIN =====
Route::middleware(['auth','admin'])->group(function () {

    Route::get('/addProduct', [AdminController::class, 'addProductPage'])->name('addProduct');
    Route::post('/addProduct', [AdminController::class, 'addProduct']);

    Route::get('/addCategory', [AdminController::class, 'addCategoryPage'])->name('addCategory');
    Route::post('/addCategory', [AdminController::class, 'addCategory']);

    Route::get('/admin/orders', [AdminController::class, 'orderPage'])->name('orders');
    Route::post('/admin/orders/status/{id}', [AdminController::class, 'updateOrderStatus']);

    Route::get('/admin/orderDetails/{id}', [AdminController::class, 'orderDetails'])->name('orderDetails');
});
