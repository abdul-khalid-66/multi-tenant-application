<?php

declare(strict_types=1);

use App\Http\Controllers\App\{
    ActivityController,
    ProfileController,
    UserController,
    ProductController,
    CategoryController,
    ProductVariantController,
    SupplierController,
    CustomerController,
    InvestmentController,
    SaleController,
    ExpenseController,
    CashInHandController,
    ProfitLossController,
    BackendController,
    InventoryLogController,
    InvoiceController,
    ProductReturnController,
    ReturnApprovalController,
    InvestmentReportController,
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


    Route::get('dashboard', [BackendController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::group(['middleware' => ['role:admin']], function () {

            Route::resource('users', UserController::class);
            // Dashboard
            Route::get('/dashboard', [BackendController::class, 'index'])->name('dashboard');
            Route::get('inventory-logs', [InventoryLogController::class, 'index'])->name('inventory-logs.index');
            Route::post('/dashboard/sales-data', [BackendController::class, 'getSalesData'])->name('dashboard.sales-data'); // dashboard sale performanc data get ajax
            Route::put('/sales/{id}/update-payment', [SaleController::class, 'updatePayment'])
            ->name('sales.updatePayment');
            // Product Relate Route Here
            Route::resource('products', ProductController::class);
            Route::resource('product-variants', ProductVariantController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('suppliers', SupplierController::class);
            Route::resource('customers', CustomerController::class);
            Route::resource('sales', SaleController::class);
            Route::resource('expenses', ExpenseController::class)->except(['create', 'edit']);
            Route::resource('returns', ProductReturnController::class);

            // Cash Flow Dashboard
            Route::prefix('cash')->group(function () {
                Route::get('in-hand', [CashInHandController::class, 'index'])->name('cash.index');
                Route::get('balance', [CashInHandController::class, 'balance'])->name('cash.balance');
            });

            // Reports
            Route::prefix('reports')->name('reports.')->group(function () {
                // Profit/Loss
                Route::get('profit-loss', [ProfitLossController::class, 'index'])->name('profit-loss');
                Route::get('profit-loss/{profitLoss}', [ProfitLossController::class, 'show'])->name('profit-loss.show');
                Route::get('profit-loss-summary', [ProfitLossController::class, 'summary'])->name('profit-loss.summary');
            });
            // Product Variants Routes
            // Inventory Logs Routes
            
            Route::prefix('invoices')->group(function () {
                Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
                Route::get('/search', [InvoiceController::class, 'search'])->name('invoices.search');
                Route::get('/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
                Route::get('/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');
                Route::get('/{id}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
            });
                        
            // Return/refund 
            Route::prefix('returns')->group(function () {
                Route::get('/{return}/approve', [ReturnApprovalController::class, 'approve'])->name('returns.approve');
                Route::get('/{return}/reject', [ReturnApprovalController::class, 'reject'])->name('returns.reject');
                Route::post('/{return}/process', [ReturnApprovalController::class, 'process'])->name('returns.process');
                
            });
            
            
            Route::get('returns_product/analytics', [ProductReturnController::class, 'analytics'])->name('returns.analytics');
            Route::get('returns_product/approve', [ProductReturnController::class, 'approval'])->name('returns.approvals');
            


                
            // Sales
            Route::prefix('sales')->group(function () {
                Route::get('/{sale}/print', [SaleController::class, 'printInvoice'])->name('sales.print');
                Route::get('/{sale}/invoice-pdf', [SaleController::class, 'generateInvoicePDF'])->name('sales.invoice.pdf');
                Route::get('/', [SaleController::class, 'index'])->name('sales.index');
                Route::get('/invoice/{invoice_no}', [SaleController::class, 'showByInvoice'])->name('sales.invoice');
                Route::get('/{sale}/items', [\App\Http\Controllers\App\SaleController::class, 'getSaleItems'])->name('sales.items');
            });

            // Reports
            // Route::prefix('reports')->group(function () {
            //     Route::get('/financial', [ReportController::class, 'financial'])->name('reports.financial');
            // });

            // // Inventory
            // Route::prefix('inventory')->group(function () {
            //     Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
            //     Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
            // });

            // // Returns
            // Route::prefix('returns')->group(function () {
            //     Route::get('/', [ProductReturnController::class, 'index'])->name('returns.index');
            // });

            // // Activities
            Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

            // // Tasks
            // Route::prefix('tasks')->group(function () {
            //     Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
            // });
            // });

            Route::get('/inventory/low-stock', [ProductController::class, 'lowStock'])
            ->name('inventory.low-stock');


            // Route::resource('investments', InvestmentController::class);


            // investment routes

            Route::prefix('investments')->group(function() {
                Route::get('/', [InvestmentController::class, 'index'])->name('investments.index');
                Route::get('/create', [InvestmentController::class, 'create'])->name('investments.create');
                Route::post('/', [InvestmentController::class, 'store'])->name('investments.store');
                Route::get('/{investment}', [InvestmentController::class, 'show'])->name('investments.show');
                Route::get('/{investment}/edit', [InvestmentController::class, 'edit'])->name('investments.edit');
                Route::put('/{investment}', [InvestmentController::class, 'update'])->name('investments.update');
                Route::delete('/{investment}', [InvestmentController::class, 'destroy'])->name('investments.destroy');
                
                // Reports
                Route::get('/reports/summary', [InvestmentReportController::class, 'summary'])->name('investments.reports.summary');
                Route::get('/reports/returns', [InvestmentReportController::class, 'returns'])->name('investments.reports.returns');
            });






            // Supplier Related Routes Here
            


            // Main supplier resource
            Route::resource('suppliers', SupplierController::class);

            // Supplier performance dashboard

            
            // Supplier performance dashboard
            // Route::get('supplier/performance', [SupplierController::class, 'performance'])
            //     ->name('suppliers.performance');
                
            // Supplier performance
            Route::get('supplier/{supplier}/performance', [SupplierController::class, 'performance'])
                ->name('suppliers.performance');

            // Supplier products
            Route::get('supplier/{supplier}/products', [SupplierController::class, 'products'])
                ->name('suppliers.products');
                
  
        });
    });

    require __DIR__ . '/tenant-auth.php';
});
