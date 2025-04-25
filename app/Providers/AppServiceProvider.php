<?php

namespace App\Providers;

use App\Models\ProductReturn;
use Illuminate\Support\ServiceProvider;
use App\Models\ProductVariant;
use App\Models\ReturnDetail;
use App\Models\SaleDetail;
use App\Observers\InventoryLogObserver;
use App\Observers\ProductReturnObserver;
use App\Observers\ReturnDetailObserver;
use App\Observers\SaleDetailObserver;
use App\Models\Expense;
use App\Models\ProfitLoss;
use App\Observers\ExpenseObserver;
use App\Observers\ProfitLossObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
