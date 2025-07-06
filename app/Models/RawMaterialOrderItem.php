<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialOrderItem extends Model
{
    protected $fillable = ['raw_material_order_id', 'raw_material_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(RawMaterialOrder::class, 'raw_material_order_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }
}
