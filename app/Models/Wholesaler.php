<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    /** @use HasFactory<\Database\Factories\WholesalerFactory> */
    use HasFactory;
    protected $fillable=[
        'user_id'
    ];
    public function checkpdf(){
        request()->validate(['required']);
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
