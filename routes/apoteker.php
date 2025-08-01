<?php

use App\Http\Controllers\Apoteker\ProductController;
use App\Http\Controllers\Apoteker\SalesController;
use App\Http\Controllers\Apoteker\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'apoteker'])->prefix('apoteker')->name('apoteker.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/history', [SalesController::class, 'index'])->name('history');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/store', [SalesController::class, 'store'])->name('store');
        Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
        Route::get('/{sale}/print', [SalesController::class, 'print'])->name('print');
        Route::get('/product/{id}', [SalesController::class, 'getProductDetails'])->name('product.details');
    });
    
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [SalesController::class, 'reports'])->name('sales');
    });
    
});
