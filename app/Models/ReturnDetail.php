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

    // In your ReturnDetail model
    // public function getPreviouslyReturned()
    // {
    //     if (!$this->saleDetail) {
    //         return 0;
    //     }
        
    //     return self::where('product_id', $this->product_id)
    //         ->where('variant_id', $this->variant_id)
    //         ->where('id', '!=', $this->id)
    //         ->sum('quantity_returned');
    // }

    public function getPreviouslyReturned()
    {
        $saleDetail = $this->saleDetail();
        if (!$saleDetail) {
            return 0;
        }
        
        return self::where('return_id', '!=', $this->return_id)
            ->whereHas('return', function($query) use ($saleDetail) {
                $query->where('sale_id', $saleDetail->sale_id);
            })
            ->where('product_id', $this->product_id)
            ->where('variant_id', $this->variant_id)
            ->sum('quantity_returned');
    }

    // In ReturnDetail.php
    public function saleDetail()
    {
        // Get the sale_id from the parent return
        $saleId = $this->return->sale_id;
        
        // Find the matching sale detail for this product/variant combination
        return SaleDetail::where('sale_id', $saleId)
            ->where('product_id', $this->product_id)
            ->where('variant_id', $this->variant_id)
            ->first();
    }
}
