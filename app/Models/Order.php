<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'location', 'mobile', 'total', 'supply_center_id'];
    public function items() {
    return $this->hasMany(OrderItem::class);
}

public function user() {
    return $this->belongsTo(User::class, 'user_id');
}

public function supplyCenter() {
    return $this->belongsTo(SupplyCenter::class);
}
}
