<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplyCenter extends Model
{
    protected $fillable = ['name', 'location'];


    public function workers() { return $this->hasMany(Worker::class); }
public function stocks() { return $this->hasMany(Stock::class); }
public function sales() { return $this->hasMany(Sale::class); }

}
