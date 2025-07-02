<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'raw_material_id',
        'user_id',
        'on_hand',
        'on_order',
        'stock_status',
        'delivery_status',
        'delivered_on',
        'expected_delivery',
    ];

    // 🔁 Relationships

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
