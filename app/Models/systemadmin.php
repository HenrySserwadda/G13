<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Systemadmin extends Model
{
    /** @use HasFactory<\Database\Factories\SystemAdminFactory> */
    use HasFactory;
    /*  i am supposed to u a relationship here between the user and the systemadmin */
    public static function generateSystemAdminId($id){
        $prefix='SA';
        $lastUser = User::where('category', 'systemadmin')
            ->whereNotNull('user_id')
            ->orderBy('user_id', 'desc')
            ->first();

        if ($lastUser && preg_match('/\d+/', $lastUser->user_id, $matches)) {
            $lastNumber = (int)$matches[0];
        } else {
            $lastNumber = 0;
        }
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $nextNumber;
    }
}

