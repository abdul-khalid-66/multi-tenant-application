<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'type',
        'description',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    protected $dates = ['date', 'deleted_at'];

    // Investment types
    public const TYPES = [
        'initial' => 'Initial Investment',
        'additional' => 'Additional Investment',
        'loan' => 'Business Loan',
        'equity' => 'Equity Investment'
    ];
}
