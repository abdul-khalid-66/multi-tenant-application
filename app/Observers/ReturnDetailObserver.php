<?php

namespace App\Observers;

use App\Models\ReturnDetail;

class ReturnDetailObserver
{
    /**
     * Handle the ReturnDetail "created" event.
     */
    public function created(ReturnDetail $detail)
    {
        if ($detail->return->status === 'approved' && $detail->variant_id) {
            $detail->variant->increment('stock_quantity', $detail->quantity_returned);
        }
    }

    /**
     * Handle the ReturnDetail "updated" event.
     */
    public function updated(ReturnDetail $returnDetail): void
    {
        //
    }

    /**
     * Handle the ReturnDetail "deleted" event.
     */
    public function deleted(ReturnDetail $returnDetail): void
    {
        //
    }

    /**
     * Handle the ReturnDetail "restored" event.
     */
    public function restored(ReturnDetail $returnDetail): void
    {
        //
    }

    /**
     * Handle the ReturnDetail "force deleted" event.
     */
    public function forceDeleted(ReturnDetail $returnDetail): void
    {
        //
    }

    public function saving(ReturnDetail $detail)
    {
        // Auto-calculate total refund amount
        $detail->total_refund_amount = $detail->quantity_returned * $detail->refund_amount_per_unit;
    }

    public function saved(ReturnDetail $detail)
    {
        // Update parent return total
        $detail->return->update([
            'total_refund_amount' => $detail->return->returnDetails->sum('total_refund_amount')
        ]);
    }
}
