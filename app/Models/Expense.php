<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'category',
        'description',
        'date',
        'verified_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime'
    ];

    public const CATEGORIES = [
        'rent' => 'Rent',
        'utilities' => 'Utilities',
        'salaries' => 'Salaries',
        'inventory' => 'Inventory',
        'maintenance' => 'Maintenance',
        'marketing' => 'Marketing'
    ];
}
