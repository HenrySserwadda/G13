<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'selling_price',
        'stock_quantity',
        'image',
    ];

}
