<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\CashInHandDetail;

class ExpenseObserver
{
    public function created(Expense $expense)
    {
        CashInHandDetail::create([
            'date' => $expense->date,
            'amount' => -$expense->amount, // Negative for expenses
            'transaction_type' => 'expense',
            'reference_id' => $expense->id,
            'reference_type' => Expense::class
        ]);
    }

    public function updated(Expense $expense)
    {
        if ($expense->isDirty('amount')) {
            $cashDetail = CashInHandDetail::where([
                'reference_id' => $expense->id,
                'reference_type' => Expense::class
            ])->first();

            if ($cashDetail) {
                $cashDetail->update([
                    'amount' => -$expense->amount,
                    'date' => $expense->date
                ]);
            }
        }
    }

    public function deleted(Expense $expense)
    {
        CashInHandDetail::where([
            'reference_id' => $expense->id,
            'reference_type' => Expense::class
        ])->delete();
    }

    public function restored(Expense $expense)
    {
        CashInHandDetail::withTrashed()
            ->where([
                'reference_id' => $expense->id,
                'reference_type' => Expense::class
            ])
            ->restore();
    }
}
