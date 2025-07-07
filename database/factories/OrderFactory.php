<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    return [
        'user_id' => User::factory(),
        'location' => $this->faker->city(),
        'mobile' => $this->faker->phoneNumber(),
        'total' => 0,
        'created_at' => $this->faker->dateTimeBetween('-12 months', 'now'),
        'updated_at' => now(),
    ];
}
}
