<?php

namespace App\Observers;

use App\Models\ProfitLoss;
use App\Models\CashInHandDetail;

class ProfitLossObserver
{
    public function created(ProfitLoss $profitLoss)
    {
        $amount = $profitLoss->profit > 0 ? $profitLoss->profit : -$profitLoss->loss;

        CashInHandDetail::create([
            'date' => $profitLoss->date,
            'amount' => $amount,
            'transaction_type' => $profitLoss->profit > 0 ? 'profit' : 'loss',
            'reference_id' => $profitLoss->id,
            'reference_type' => ProfitLoss::class
        ]);
    }

    public function updated(ProfitLoss $profitLoss)
    {
        if ($profitLoss->isDirty(['profit', 'loss', 'date'])) {
            $cashDetail = CashInHandDetail::where([
                'reference_id' => $profitLoss->id,
                'reference_type' => ProfitLoss::class
            ])->first();

            if ($cashDetail) {
                $amount = $profitLoss->profit > 0 ? $profitLoss->profit : -$profitLoss->loss;

                $cashDetail->update([
                    'amount' => $amount,
                    'date' => $profitLoss->date,
                    'transaction_type' => $profitLoss->profit > 0 ? 'profit' : 'loss'
                ]);
            }
        }
    }

    public function deleted(ProfitLoss $profitLoss)
    {
        CashInHandDetail::where([
            'reference_id' => $profitLoss->id,
            'reference_type' => ProfitLoss::class
        ])->delete();
    }

    public function restored(ProfitLoss $profitLoss)
    {
        CashInHandDetail::withTrashed()
            ->where([
                'reference_id' => $profitLoss->id,
                'reference_type' => ProfitLoss::class
            ])
            ->restore();
    }
}
