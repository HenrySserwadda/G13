<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
protected $fillable = [
    'product_name',
    'type',
    'description',
    'selling_price',
    'stock_quantity',
    'product_image',
];

    // If you had protected $guarded = []; instead, it means all are fillable,
    // but it's more explicit and safer to use $fillable.
    // If you had $guarded with specific fields, remove them or ensure they're not duplicated in $fillable.

    // ... rest of your model code
}