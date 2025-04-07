<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'sale_id',
        'customer_id',
        'return_date',
        'reason',
        'status',
        'total_refund_amount'
    ];

    protected $casts = [
        'return_date' => 'datetime',
        'total_refund_amount' => 'decimal:2'
    ];

    public const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'processed' => 'Processed'
    ];

    public const REASONS = [
        'defective' => 'Defective Product',
        'wrong_item' => 'Wrong Item Shipped',
        'changed_mind' => 'Customer Changed Mind',
        'late_delivery' => 'Late Delivery',
        'other' => 'Other'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}
