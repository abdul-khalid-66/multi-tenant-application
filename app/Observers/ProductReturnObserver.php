<?php

namespace App\Observers;

use App\Models\ProductReturn;
use App\Models\InventoryLog;
use App\Models\ProductVariant;

class ProductReturnObserver
{
    /**
     * Handle the ProductReturn "created" event.
     */
    public function created(ProductReturn $productReturn): void
    {
        if ($productReturn->status === 'approved') {
            $this->processReturn($productReturn);
        }
    }

    /**
     * Handle the ProductReturn "updated" event.
     */
    public function updated(ProductReturn $productReturn): void
    {
        // Only process if status changed to approved
        if ($productReturn->isDirty('status') && $productReturn->status === 'approved') {
            $this->processReturn($productReturn);
        }

        // If status changed from approved to something else
        if (
            $productReturn->isDirty('status') &&
            $productReturn->getOriginal('status') === 'approved' &&
            $productReturn->status !== 'approved'
        ) {
            $this->reverseReturn($productReturn);
        }
    }

    /**
     * Handle the ProductReturn "deleted" event.
     */
    public function deleted(ProductReturn $productReturn): void
    {
        if ($productReturn->status === 'approved') {
            $this->reverseReturn($productReturn, 'return_deleted');
        }
    }

    /**
     * Handle the ProductReturn "restored" event.
     */
    public function restored(ProductReturn $productReturn): void
    {
        if ($productReturn->status === 'approved') {
            $this->processReturn($productReturn, 'return_restored');
        }
    }

    /**
     * Process approved return 08/04/2025
     */
    // protected function processReturn(ProductReturn $productReturn, string $reason = 'return_approved'): void
    // {
    //     $productReturn->returnDetails->each(function ($detail) use ($productReturn, $reason) {
    //         $variant = ProductVariant::find($detail->variant_id);

    //         if ($variant) {
    //             $oldStock = $variant->stock_quantity;
    //             $variant->increment('stock_quantity', $detail->quantity_returned);

    //             InventoryLog::create([
    //                 'product_id' => $detail->product_id,
    //                 'variant_id' => $detail->variant_id,
    //                 'old_stock' => $oldStock,
    //                 'new_stock' => $variant->stock_quantity,
    //                 'reason' => $reason,
    //                 'date' => now(),
    //                 'reference_id' => $productReturn->id,
    //                 'reference_type' => ProductReturn::class
    //             ]);
    //         }
    //     });

    //     // Additional business logic could go here:
    //     // - Create refund transaction
    //     // - Notify customer
    //     // - Update sales analytics
    // }


    protected function processReturn(ProductReturn $productReturn, string $reason = 'return_approved'): void
    {
        ProductVariant::withoutEvents(function () use ($productReturn, $reason) {
            $productReturn->returnDetails->each(function ($detail) use ($productReturn, $reason) {
                $variant = ProductVariant::find($detail->variant_id);

                if ($variant) {
                    $oldStock = $variant->stock_quantity;
                    $variant->increment('stock_quantity', $detail->quantity_returned);

                    InventoryLog::create([
                        'product_id' => $detail->product_id,
                        'variant_id' => $detail->variant_id,
                        'old_stock' => $oldStock,
                        'new_stock' => $variant->stock_quantity,
                        'reason' => $reason,
                        'date' => now(),
                        'reference_id' => $productReturn->id,
                        'reference_type' => ProductReturn::class
                    ]);
                }
            });
        });
    }

    /**
     * Reverse an approved return
     */
    protected function reverseReturn(ProductReturn $productReturn, string $reason = 'return_reversed'): void
    {
        $productReturn->details->each(function ($detail) use ($productReturn, $reason) {
            $variant = ProductVariant::find($detail->variant_id);

            if ($variant) {
                $oldStock = $variant->stock_quantity;
                $variant->decrement('stock_quantity', $detail->quantity_returned);

                InventoryLog::create([
                    'product_id' => $detail->product_id,
                    'variant_id' => $detail->variant_id,
                    'old_stock' => $oldStock,
                    'new_stock' => $variant->stock_quantity,
                    'reason' => $reason,
                    'date' => now(),
                    'reference_id' => $productReturn->id,
                    'reference_type' => ProductReturn::class
                ]);
            }
        });
    }
}
