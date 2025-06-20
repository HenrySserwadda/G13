<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



// Model
class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'raw_material_id',
        'type',
        'quantity',
        'new_stock',
        'reason',
        'notes',
        'user_id',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}