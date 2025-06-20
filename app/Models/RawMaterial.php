<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'unit_of_measure',
        'current_stock',
        'minimum_stock',
        'unit_cost',
        'supplier_id',
        'location',
        'image_path',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    // Helpers
    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function stockValue()
    {
        return $this->current_stock * $this->unit_cost;
    }
    public function scopeFilter($query, array $filters)
{
    $query->when($filters['search'] ?? false, function ($query, $search) {
        $query->where(function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
        });
    });

    $query->when($filters['category'] ?? false, function ($query, $category) {
        $query->where('category', $category);
    });

    $query->when($filters['stock_status'] ?? false, function ($query, $status) {
        if ($status === 'low') {
            $query->whereColumn('current_stock', '<=', 'minimum_stock');
        } elseif ($status === 'adequate') {
            $query->whereColumn('current_stock', '>', 'minimum_stock');
        }
    });
}
}