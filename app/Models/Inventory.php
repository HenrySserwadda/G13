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

    protected $casts = [
        'delivered_on' => 'date',
        'expected_delivery' => 'date',
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

    public function getOnOrderAttribute($value)
    {
        // If the DB value is not zero, return it (for legacy/manual entries)
        if ($value) return $value;

        $userId = $this->user_id;
        $rawMaterialId = $this->raw_material_id;

        return \App\Models\RawMaterialOrderItem::whereHas('order', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'pending');
            })
            ->where('raw_material_id', $rawMaterialId)
            ->sum('quantity');
    }
}
