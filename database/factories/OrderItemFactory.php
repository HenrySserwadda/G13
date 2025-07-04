<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    $product = Product::inRandomOrder()->first();

    $quantity = rand(1, 5);

    return [
        'product_id' => $product->id,
        'quantity' => $quantity,
        'price' => $product->price,
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
}
