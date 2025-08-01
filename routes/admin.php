<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        
        Route::get('/customers/list', [UserController::class, 'customers'])->name('customers');
        Route::get('/pharmacists/list', [UserController::class, 'pharmacists'])->name('pharmacists');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        
        Route::get('/expired', function () {
            return view('admin.products.expired');
        })->name('expired');
    });

    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{id}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', function () {
            return view('admin.customers.index');
        })->name('index');
        
        Route::get('/create', function () {
            return view('admin.customers.create');
        })->name('create');
        
        Route::get('/{id}', function ($id) {
            return view('admin.customers.show', compact('id'));
        })->name('show');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.customers.edit', compact('id'));
        })->name('edit');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', function () {
            return view('admin.transactions.index');
        })->name('index');
        
        Route::get('/purchases', function () {
            return view('admin.transactions.purchases');
        })->name('purchases');
        
        Route::get('/{id}', function ($id) {
            return view('admin.transactions.show', compact('id'));
        })->name('show');
    });

    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('/create', [PurchaseController::class, 'create'])->name('create');
        Route::post('/', [PurchaseController::class, 'store'])->name('store');
        Route::get('/history', [PurchaseController::class, 'history'])->name('history');
        Route::get('/product/{id}', [PurchaseController::class, 'getProductDetails'])->name('product.details');
        Route::get('/{id}', [PurchaseController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{id}', [PurchaseController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/receive', [PurchaseController::class, 'receive'])->name('receive');
        Route::post('/{id}/receive', [PurchaseController::class, 'processReceive'])->name('process-receive');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/history', [SalesController::class, 'index'])->name('history');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/store', [SalesController::class, 'store'])->name('store');
        Route::post('/{sale}/refund', [SalesController::class, 'refund'])->name('refund');
        Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
        Route::get('/{sale}/print', [SalesController::class, 'print'])->name('print');
        Route::get('/product/{id}', [SalesController::class, 'getProductDetails'])->name('product.details');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [SalesController::class, 'reports'])->name('sales');
        
        Route::get('/financial', function () {
            return view('admin.reports.financial');
        })->name('financial');
        
        Route::get('/inventory', function () {
            return view('admin.reports.inventory');
        })->name('inventory');
        
        Route::get('/users', function () {
            return view('admin.reports.users');
        })->name('users');
    });
});

