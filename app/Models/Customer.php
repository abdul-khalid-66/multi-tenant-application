<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contact',
        'address'
    ];

    protected $dates = ['deleted_at'];

    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Relationship with Returns
    public function returns()
    {
        return $this->hasMany(ProductReturn::class);
    }
}
