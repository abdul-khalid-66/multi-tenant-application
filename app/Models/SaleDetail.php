<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'product_id',
        'variant_id',
        'quantity',
        'cost_price',
        'sell_price',
        'unit',
        'line_item_note',
        'total_price'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getReturnableQuantityAttribute()
    {
        $returned = ReturnDetail::whereHas('return', function($query) {
                $query->where('sale_id', $this->sale_id);
            })
            ->where('product_id', $this->product_id)
            ->where('variant_id', $this->variant_id)
            ->sum('quantity_returned');
        
        return $this->quantity - $returned;
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'product_id', 'product_id')->where('variant_id', $this->variant_id);
    }
}
