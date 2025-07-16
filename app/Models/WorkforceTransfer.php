<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkforceTransfer extends Model
{
    public function worker() { return $this->belongsTo(Worker::class); }
public function fromCenter() { return $this->belongsTo(SupplyCenter::class, 'from_center_id'); }
public function toCenter() { return $this->belongsTo(SupplyCenter::class, 'to_center_id'); }

}
