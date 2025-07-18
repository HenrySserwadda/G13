<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'supply_center_id',
        'monthly_sales',
        'sales_month',
        'order_id'
    ];

    protected $casts = [
        'sales_month' => 'date',
    ];

    public function supplyCenter() { 
        return $this->belongsTo(SupplyCenter::class); 
    }

    public function order() { 
        return $this->belongsTo(Order::class); 
    }
}
