<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesalerRetailerInventory extends Model
{
    protected $table = 'wholesaler_retailer_inventories';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'stock_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}