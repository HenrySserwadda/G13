<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
     protected $fillable = [
        'name',
        'supply_center_id'
    ];
    public function supplyCenter() { return $this->belongsTo(SupplyCenter::class); }

}
