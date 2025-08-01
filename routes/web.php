<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CustomerController;
use App\Http\Controllers\Public\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{id}', [HomeController::class, 'show'])->name('public.products.show');

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($user->role === 'apoteker') {
        return redirect('/apoteker/dashboard');
    } else {
        return redirect()->route('home');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/profile/customer', [CustomerController::class, 'profile'])->name('public.profile');
    Route::put('/profile/customer', [CustomerController::class, 'updateProfile'])->name('public.profile.update');
    Route::put('/profile/password', [CustomerController::class, 'updatePassword'])->name('public.password.update');
    
    Route::get('/sales', [CustomerController::class, 'sales'])->name('public.sales');
    Route::get('/sales/{id}', [CustomerController::class, 'saleDetail'])->name('public.sale-detail');
    
    Route::get('/transactions', [CustomerController::class, 'sales'])->name('public.transactions');
    Route::get('/transactions/{id}', [CustomerController::class, 'saleDetail'])->name('public.transaction-detail');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('public.cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('public.cart.add');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('public.cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'removeFromCart'])->name('public.cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('public.cart.clear');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('public.cart.checkout');
    Route::post('/cart/checkout', [CustomerController::class, 'processCartCheckout'])->name('public.cart.process-checkout');
    
    Route::get('/checkout/{product}', [CustomerController::class, 'checkout'])->name('public.checkout');
    Route::post('/checkout/{product}', [CustomerController::class, 'processSale'])->name('public.process-sale');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/apoteker.php';
