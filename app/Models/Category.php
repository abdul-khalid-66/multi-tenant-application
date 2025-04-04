<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_name',
        'subcategory'
    ];

    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }



    public function parent()
    {
        return $this->belongsTo(Category::class, 'subcategory', 'category_name');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'subcategory', 'category_name');
    }
}
