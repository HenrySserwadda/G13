<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'address',
        'email',  
        'phone',
        'materials_supplied',
        'user_id'   
        ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }    
    }