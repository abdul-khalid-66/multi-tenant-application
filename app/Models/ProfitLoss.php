<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfitLoss extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'profit',
        'loss',
        'description',
        'category',
        'verified_by',
        'date'
    ];

    protected $casts = [
        'profit' => 'decimal:2',
        'loss' => 'decimal:2',
        'date' => 'datetime'
    ];

    public const CATEGORIES = [
        'operational' => 'Operational',
        'sales' => 'Sales',
        'inventory' => 'Inventory',
        'discount' => 'Discount',
        'damage' => 'Damaged Goods'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
