<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function supplyCenter() { return $this->belongsTo(SupplyCenter::class); }

}
