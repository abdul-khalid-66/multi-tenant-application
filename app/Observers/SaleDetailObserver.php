<?php

namespace App\Observers;

use App\Models\SaleDetail;
use App\Models\ProductVariant;
use App\Models\InventoryLog;

class SaleDetailObserver
{
    /**
     * Handle the SaleDetail "created" event.
     */
    public function created(SaleDetail $saleDetail): void
    {
        $this->updateInventory($saleDetail, 'sale_created');
        $this->updateSaleTotal($saleDetail->sale);
    }

    /**
     * Handle the SaleDetail "updated" event.
     */
    public function updated(SaleDetail $saleDetail): void
    {
        if ($saleDetail->isDirty(['quantity', 'variant_id'])) {
            $this->reverseInventory($saleDetail->getOriginal(), 'sale_updated');
            $this->updateInventory($saleDetail, 'sale_updated');
            $this->updateSaleTotal($saleDetail->sale);
        }
    }

    /**
     * Handle the SaleDetail "deleted" event.
     */
    public function deleted(SaleDetail $saleDetail): void
    {
        $this->reverseInventory($saleDetail, 'sale_deleted');
        $this->updateSaleTotal($saleDetail->sale);
    }

    /**
     * Handle the SaleDetail "restored" event.
     */
    public function restored(SaleDetail $saleDetail): void
    {
        $this->updateInventory($saleDetail, 'sale_restored');
        $this->updateSaleTotal($saleDetail->sale);
    }

    protected function updateInventory(SaleDetail $saleDetail, string $reason): void
    {
        $variant = ProductVariant::find($saleDetail->variant_id);

        if ($variant) {
            $oldStock = $variant->stock_quantity;
            ProductVariant::withoutEvents(function () use ($variant, $saleDetail) {
                $variant->decrement('stock_quantity', $saleDetail->quantity);
            });

            InventoryLog::create([
                'product_id' => $saleDetail->product_id,
                'variant_id' => $saleDetail->variant_id,
                'old_stock' => $oldStock,
                'new_stock' => $variant->stock_quantity,
                'reason' => $reason,
                'date' => now(),
                'reference_id' => $saleDetail->sale_id,
                'reference_type' => 'sale'
            ]);
        }
    }

    protected function reverseInventory(SaleDetail $saleDetail, string $reason): void
    {
        $variant = ProductVariant::find($saleDetail->variant_id);

        if ($variant) {
            $oldStock = $variant->stock_quantity;
            $variant->increment('stock_quantity', $saleDetail->quantity);

            InventoryLog::create([
                'product_id' => $saleDetail->product_id,
                'variant_id' => $saleDetail->variant_id,
                'old_stock' => $oldStock,
                'new_stock' => $variant->stock_quantity,
                'reason' => $reason,
                'date' => now(),
                'reference_id' => $saleDetail->sale_id,
                'reference_type' => 'sale'
            ]);
        }
    }

    protected function updateSaleTotal($sale): void
    {
        $sale->update([
            'total_amount' => $sale->saleDetails->sum('total_price'),
            'cost_price' => $sale->saleDetails->sum(function ($detail) {
                return $detail->cost_price * $detail->quantity;
            })
        ]);
    }
}
