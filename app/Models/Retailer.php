<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    /** @use HasFactory<\Database\Factories\RetailerFactory> */
    use HasFactory;
    protected $fillable=[
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
