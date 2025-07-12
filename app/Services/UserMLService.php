<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class UserMLService
{
    /**
     * Analyze user's purchase history to extract preferences
     */
    public function analyzeUserPreferences(User $user): array
    {
        try {
            $preferences = [
                'gender' => $user->gender,
                'preferred_styles' => [],
                'preferred_colors' => [],
                'preferred_materials' => [],
                'price_range' => ['min' => 0, 'max' => 0],
                'purchase_frequency' => 0,
                'total_spent' => 0,
                'last_purchase_date' => null,
            ];

            // Get user's order history
            $orders = Order::with(['items.product'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($orders->isEmpty()) {
                Log::info("No purchase history found for user {$user->id}");
                return $preferences;
            }

            $preferences['purchase_frequency'] = $orders->count();
            $preferences['last_purchase_date'] = $orders->first()->created_at;

            $allProducts = [];
            $totalSpent = 0;

            // Analyze each order
            foreach ($orders as $order) {
                $totalSpent += $order->total;

                foreach ($order->items as $item) {
                    $product = $item->product;
                    if (!$product) continue;

                    $allProducts[] = $product;

                    // Extract ML-specific attributes
                    if ($product->is_ml_generated) {
                        if ($product->ml_style) {
                            $preferences['preferred_styles'][] = $product->ml_style;
                        }
                        if ($product->ml_color) {
                            $preferences['preferred_colors'][] = $product->ml_color;
                        }
                        if ($product->ml_gender) {
                            $preferences['preferred_genders'][] = $product->ml_gender;
                        }
                    }

                    // For regular products, try to extract style/color from name/description
                    $this->extractProductAttributes($product, $preferences);
                }
            }

            // Calculate price range
            if (!empty($allProducts)) {
                $prices = array_map(fn($p) => $p->price, $allProducts);
                $preferences['price_range'] = [
                    'min' => min($prices),
                    'max' => max($prices),
                    'avg' => array_sum($prices) / count($prices)
                ];
            }

            $preferences['total_spent'] = $totalSpent;

            // Get most common preferences (top 3)
            $preferences['preferred_styles'] = $this->getTopPreferences($preferences['preferred_styles'], 3);
            $preferences['preferred_colors'] = $this->getTopPreferences($preferences['preferred_colors'], 3);
            $preferences['preferred_materials'] = $this->getTopPreferences($preferences['preferred_materials'], 3);

            Log::info("User preferences analyzed for user {$user->id}", $preferences);
            return $preferences;

        } catch (\Exception $e) {
            Log::error("Error analyzing user preferences: " . $e->getMessage());
            return $preferences;
        }
    }

    /**
     * Extract product attributes from regular products
     */
    private function extractProductAttributes(Product $product, array &$preferences): void
    {
        // Try to extract style from product name/description
        $text = strtolower($product->name . ' ' . $product->description);
        
        // Common bag styles
        $styles = ['backpack', 'tote', 'messenger', 'duffel', 'laptop', 'travel', 'puffer', 'classic', 'modern', 'vintage'];
        foreach ($styles as $style) {
            if (str_contains($text, $style)) {
                $preferences['preferred_styles'][] = ucfirst($style);
                break;
            }
        }

        // Common colors
        $colors = ['black', 'brown', 'blue', 'red', 'green', 'gray', 'white', 'pink', 'purple', 'yellow'];
        foreach ($colors as $color) {
            if (str_contains($text, $color)) {
                $preferences['preferred_colors'][] = ucfirst($color);
                break;
            }
        }

        // Common materials
        $materials = ['leather', 'canvas', 'nylon', 'polyester', 'cotton', 'suede'];
        foreach ($materials as $material) {
            if (str_contains($text, $material)) {
                $preferences['preferred_materials'][] = ucfirst($material);
                break;
            }
        }
    }

    /**
     * Get top N most common preferences
     */
    private function getTopPreferences(array $preferences, int $limit): array
    {
        $counts = array_count_values($preferences);
        arsort($counts);
        return array_slice(array_keys($counts), 0, $limit);
    }

    /**
     * Get personalized recommendations for a user
     */
    public function getPersonalizedRecommendations(User $user, int $limit = 10): array
    {
        $preferences = $this->analyzeUserPreferences($user);
        
        // Build query based on user preferences
        $query = Product::query();

        // Filter by gender if user has a preference
        if ($preferences['gender'] && $preferences['gender'] !== 'null') {
            $query->where(function($q) use ($preferences) {
                $q->where('ml_gender', $preferences['gender'])
                  ->orWhere('ml_gender', 'Unisex')
                  ->orWhereNull('ml_gender');
            });
        }

        // Filter by preferred styles if available
        if (!empty($preferences['preferred_styles'])) {
            $query->whereIn('ml_style', $preferences['preferred_styles']);
        }

        // Filter by preferred colors if available
        if (!empty($preferences['preferred_colors'])) {
            $query->whereIn('ml_color', $preferences['preferred_colors']);
        }

        // Filter by price range if user has purchase history
        if ($preferences['price_range']['max'] > 0) {
            $avgPrice = $preferences['price_range']['avg'];
            $query->whereBetween('price', [
                $avgPrice * 0.7, // 30% below average
                $avgPrice * 1.3  // 30% above average
            ]);
        }

        // Prioritize ML-generated products and order by popularity
        $query->where('is_ml_generated', true)
              ->orderBy('ml_popularity_score', 'desc')
              ->limit($limit);

        $products = $query->get();

        // If we don't have enough personalized products, add some general recommendations
        if ($products->count() < $limit) {
            $remaining = $limit - $products->count();
            $generalProducts = Product::where('is_ml_generated', true)
                ->whereNotIn('id', $products->pluck('id'))
                ->orderBy('ml_popularity_score', 'desc')
                ->limit($remaining)
                ->get();
            
            $products = $products->merge($generalProducts);
        }

        return $products->toArray();
    }

    /**
     * Get user's purchase history summary
     */
    public function getUserPurchaseSummary(User $user): array
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total'),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'last_order_date' => $orders->first()?->created_at,
            'favorite_products' => [],
            'recent_purchases' => []
        ];

        // Get favorite products (most purchased)
        $productCounts = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $productId = $item->product->id;
                    $productCounts[$productId] = ($productCounts[$productId] ?? 0) + $item->quantity;
                }
            }
        }

        arsort($productCounts);
        $topProductIds = array_slice(array_keys($productCounts), 0, 5);
        $summary['favorite_products'] = Product::whereIn('id', $topProductIds)->get()->toArray();

        // Get recent purchases
        $summary['recent_purchases'] = $orders->take(5)->map(function($order) {
            return [
                'order_id' => $order->id,
                'date' => $order->created_at,
                'total' => $order->total,
                'items' => $order->items->map(function($item) {
                    return [
                        'product_name' => $item->product->name ?? 'Unknown',
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ];
                })->toArray()
            ];
        })->toArray();

        return $summary;
    }
} 