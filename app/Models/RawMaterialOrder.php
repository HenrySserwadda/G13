<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialOrder extends Model
{
     protected $fillable = ['supplier_user_id', 'user_id', 'status'];

    public function items()
    {
        return $this->hasMany(RawMaterialOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
