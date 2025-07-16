<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function supplyCenter() { return $this->belongsTo(SupplyCenter::class); }

}
