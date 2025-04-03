<?php

declare(strict_types=1);

use App\Http\Controllers\App\{
    ProfileController,
    UserController,
    ProductController,
    CategoryController,
    ProductVariantController,
    SupplierController,
    CustomerController,
    InvestmentController,
    SaleController,
};
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return view('app.welcome');
    });

    Route::get('/dashboard', function () {
        return view('app.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::group(['middleware' => ['role:admin']], function () {
            Route::resource('users', UserController::class);

            Route::resource('products', ProductController::class);

            Route::resource('categories', CategoryController::class);


            // Standard resource routes
            Route::resource('product-variants', ProductVariantController::class);


            Route::resource('suppliers', SupplierController::class);


            Route::resource('customers', CustomerController::class);


            Route::resource('investments', InvestmentController::class);

            Route::resource('sales', SaleController::class);
            Route::get('sales/invoice/{invoice_no}', [SaleController::class, 'showByInvoice'])->name('sales.invoice');
        });
    });

    require __DIR__ . '/tenant-auth.php';
});
