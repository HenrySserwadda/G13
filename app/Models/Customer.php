<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable=[
        'user_id',
        'date_of_birth'
    ];
    public function orders(){
        return $this->hasMany(PurchaseOrder::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
