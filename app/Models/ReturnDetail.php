<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'return_id',
        'product_id',
        'variant_id',
        'quantity_returned',
        'refund_amount_per_unit',
        'total_refund_amount'
    ];

    protected $casts = [
        'refund_amount_per_unit' => 'decimal:2',
        'total_refund_amount' => 'decimal:2'
    ];

    public function return()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
