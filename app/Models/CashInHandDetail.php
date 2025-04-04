<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashInHandDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'amount',
        'transaction_type',
        'reference_id',
        'reference_type'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime'
    ];

    public const TYPES = [
        'sale' => 'Sale',
        'investment' => 'Investment',
        'expense' => 'Expense',
        'refund' => 'Refund',
        'withdrawal' => 'Withdrawal',
        'deposit' => 'Deposit'
    ];

    public function reference()
    {
        return $this->morphTo();
    }
}
