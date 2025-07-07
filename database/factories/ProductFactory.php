<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    return [
        'name' => $this->faker->word(),
        'description' => $this->faker->sentence(),
        'price' => $this->faker->randomFloat(2, 5, 10000),
        'image' => $this->faker->imageUrl(),
        'quantity' => $this->faker->numberBetween(10, 100),
        'created_at' => now()->subMonths(rand(0, 12)),
        'updated_at' => now(),
    ];
}

}
