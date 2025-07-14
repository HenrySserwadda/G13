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
        'is_ml_generated',
        'ml_style',
        'ml_color',
        'ml_gender',
        'ml_expires_at',
        'ml_popularity_score',
        // Add any other fields that you want to be mass assignable
    ];

    protected $casts = [
        'quantity' => 'integer',
        'remake_quantity' => 'integer',
        'is_ml_generated' => 'boolean',
        'ml_popularity_score' => 'integer',
        'ml_expires_at' => 'datetime',
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

    // Check if product is ML-generated
    public function isMLGenerated()
    {
        return $this->is_ml_generated;
    }

    // Check if ML product has expired
    public function isMLExpired()
    {
        return $this->is_ml_generated && $this->ml_expires_at && $this->ml_expires_at->isPast();
    }

    // Increment popularity score for ML products
    public function incrementPopularity()
    {
        if ($this->is_ml_generated) {
            $this->increment('ml_popularity_score');
        }
    }

    // Scope to get only ML-generated products
    public function scopeMLGenerated($query)
    {
        return $query->where('is_ml_generated', true);
    }

    // Scope to get only non-expired ML products
    public function scopeActiveML($query)
    {
        return $query->where('is_ml_generated', true)
                    ->where(function($q) {
                        $q->whereNull('ml_expires_at')
                          ->orWhere('ml_expires_at', '>', now());
                    });
    }

    // Clean up expired ML products
    public static function cleanupExpiredML()
    {
        return static::where('is_ml_generated', true)
                    ->where('ml_expires_at', '<', now())
                    ->delete();
    }
}