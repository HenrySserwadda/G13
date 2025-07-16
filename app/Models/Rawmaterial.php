<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Assuming User model exists

class Rawmaterial extends Model
{
    /** @use HasFactory<\Database\Factories\RawmaterialFactory> */
    use HasFactory;
    protected $table = 'raw_materials'; // Specify the table name if it differs from the default
    protected $fillable = [
        'name',
        'type',
        'quantity',
        'unit_price',
        'user_id', // Assuming a foreign key relationship with User
        'supplier_id', // Assuming a foreign key relationship with Supplier
        'unit', 
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function invnetories()
    {
        return $this->hasMany(Inventory::class);
    }
}
