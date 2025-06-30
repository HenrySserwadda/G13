<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    /** @use HasFactory<\Database\Factories\WholesalerFactory> */
    use HasFactory;
    public function checkpdf(){
        request()->validate(['required']);
    }
}
