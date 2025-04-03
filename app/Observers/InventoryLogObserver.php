<?php

namespace App\Observers;

use App\Models\ProductVariant;
use App\Models\InventoryLog;

class InventoryLogObserver
{
    /**
     * Handle the ProductVariant "created" event.
     */
    public function created(ProductVariant $productVariant): void
    {
        //
    }

    /**
     * Handle the ProductVariant "updated" event.
     */
    public function updated(ProductVariant $productVariant): void
    {
        if ($productVariant->isDirty('stock_quantity')) {
            InventoryLog::create([
                'product_id' => $productVariant->product_id,
                'variant_id' => $productVariant->id,
                'old_stock' => $productVariant->getOriginal('stock_quantity'),
                'new_stock' => $productVariant->stock_quantity,
                'reason' => 'manual_adjustment', // Can be dynamic based on context
                'date' => now()
            ]);
        }
    }

    /**
     * Handle the ProductVariant "deleted" event.
     */
    public function deleted(ProductVariant $productVariant): void
    {
        //
    }

    /**
     * Handle the ProductVariant "restored" event.
     */
    public function restored(ProductVariant $productVariant): void
    {
        //
    }

    /**
     * Handle the ProductVariant "force deleted" event.
     */
    public function forceDeleted(ProductVariant $productVariant): void
    {
        //
    }
}
