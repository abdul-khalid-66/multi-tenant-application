<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'variant_id',
        'invoice_no',
        'total_amount',
        'cost_price',
        'date',
        'customer_id',
        'payment_status',
        'payment_method',
        'discount',
        'tax'
    ];

    protected $casts = [
        'date'          => 'datetime',
        'total_amount'  => 'decimal:2',
        'cost_price'    => 'decimal:2',
        'discount'      => 'decimal:2',
        'tax'           => 'decimal:2'
    ];

    protected $dates = ['date', 'deleted_at'];

    // Payment statuses
    public const PAYMENT_STATUSES = [
        'paid'      => 'Paid',
        'pending'   => 'Pending',
        'partial'   => 'Partially Paid',
        'failed'    => 'Failed'
    ];

    // Payment methods
    public const PAYMENT_METHODS = [
        'cash'          => 'Cash',
        'credit_card'   => 'Credit Card',
        'bank_transfer' => 'Bank Transfer',
        'digital_wallet' => 'Digital Wallet'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    protected static function booted()
    {
        static::creating(function ($sale) {
            $sale->invoice_no = $sale->invoice_no ?? 'INV-' . strtoupper(uniqid());
        });
    }


    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }

    // Add this relationship 08/04/2025
    // public function details()
    // {
    //     return $this->hasMany(SaleDetail::class);
    // }
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function returns()
    {
        return $this->hasMany(ProductReturn::class, 'sale_id');
    }
    // Remove payments relationship if exists
}
