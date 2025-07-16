<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\WorkforceService;

class WorkforceAllocation extends Model
{
    protected $fillable = [
    'supply_center_id',
    'sales',
    'stock',
    'allocated_workers',
    'status',
    'recommendation_reason',
    'performance_score',
];
    public function supplyCenter()
    {
   
        return $this->belongsTo(SupplyCenter::class);
    }
}

