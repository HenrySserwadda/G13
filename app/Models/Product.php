<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'quantity',
        'stock_status',
        'remake_quantity',
        'remake_status',
        // Add any other fields that you want to be mass assignable
    ];

    protected $casts = [
        'quantity' => 'integer',
        'remake_quantity' => 'integer',
    ];

    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    // Get stock status based on quantity
    public function getStockStatusAttribute($value)
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->quantity < 5) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    // Get stock status badge class
    public function getStockStatusBadgeClassAttribute()
    {
        $status = $this->stock_status;
        switch ($status) {
            case 'in_stock':
                return 'bg-green-100 text-green-800';
            case 'low_stock':
                return 'bg-yellow-100 text-yellow-800';
            case 'out_of_stock':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    // Get remake status badge class
    public function getRemakeStatusBadgeClassAttribute()
    {
        $status = $this->remake_status;
        switch ($status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'in_progress':
                return 'bg-blue-100 text-blue-800';
            case 'completed':
                return 'bg-green-100 text-green-800';
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    // Check if product needs remake
    public function needsRemake()
    {
        return $this->quantity <= 0 && $this->remake_status !== 'completed';
    }

    // Check if remake is in progress
    public function isRemakeInProgress()
    {
        return in_array($this->remake_status, ['pending', 'in_progress']);
    }
}