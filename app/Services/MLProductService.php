<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MLProductService
{
    /**
     * Create or update ML products from recommendation data
     */
    public function createOrUpdateMLProducts(array $mlProducts): array
    {
        $createdProducts = [];
        
        foreach ($mlProducts as $mlProduct) {
            try {
                // Check if product already exists (by name and ML metadata)
                $existingProduct = Product::where('name', $mlProduct['name'])
                    ->where('is_ml_generated', true)
                    ->where('ml_style', $mlProduct['style'] ?? null)
                    ->where('ml_color', $mlProduct['color'] ?? null)
                    ->where('ml_gender', $mlProduct['gender'] ?? null)
                    ->first();

                if ($existingProduct) {
                    // Update existing ML product
                    $existingProduct->update([
                        'price' => $mlProduct['price'],
                        'quantity' => $mlProduct['quantity'],
                        'image' => $mlProduct['image'],
                        'ml_expires_at' => Carbon::now()->addDays(30), // Extend expiry
                    ]);
                    $createdProducts[] = $existingProduct;
                } else {
                    // Create new ML product
                    $product = Product::create([
                        'name' => $mlProduct['name'],
                        'description' => $mlProduct['description'],
                        'price' => $mlProduct['price'],
                        'image' => $mlProduct['image'],
                        'quantity' => $mlProduct['quantity'],
                        'is_ml_generated' => true,
                        'ml_style' => $mlProduct['style'] ?? null,
                        'ml_color' => $mlProduct['color'] ?? null,
                        'ml_gender' => $mlProduct['gender'] ?? null,
                        'ml_expires_at' => Carbon::now()->addDays(30), // 30 days expiry
                        'ml_popularity_score' => 0,
                    ]);
                    $createdProducts[] = $product;
                }
            } catch (\Exception $e) {
                Log::error('Error creating ML product: ' . $e->getMessage(), [
                    'ml_product' => $mlProduct
                ]);
            }
        }
        
        return $createdProducts;
    }

    /**
     * Get ML products with optional filters
     */
    public function getMLProducts(string $gender = null, string $style = null, string $color = null): array
    {
        $query = Product::activeML();

        if ($gender) {
            $query->where('ml_gender', 'like', "%{$gender}%");
        }
        if ($style) {
            $query->where('ml_style', 'like', "%{$style}%");
        }
        if ($color) {
            $query->where('ml_color', 'like', "%{$color}%");
        }

        return $query->limit(10)->get()->toArray();
    }

    /**
     * Increment popularity score for an ML product
     */
    public function incrementPopularity(int $productId): bool
    {
        $product = Product::find($productId);
        if ($product && $product->isMLGenerated()) {
            $product->incrementPopularity();
            return true;
        }
        return false;
    }

    /**
     * Clean up expired ML products
     */
    public function cleanupExpiredProducts(): int
    {
        return Product::cleanupExpiredML();
    }

    /**
     * Get popular ML products
     */
    public function getPopularMLProducts(int $limit = 5): array
    {
        return Product::activeML()
            ->orderBy('ml_popularity_score', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
} 