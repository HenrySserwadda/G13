<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
class OrdersandProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    // Create 20 Products
    Product::factory(20)->create();

    // Create 50 Orders
    Order::factory(50)->create()->each(function ($order) {
        $items = [];
        $products = Product::inRandomOrder()->take(rand(1, 4))->get();
        $total = 0;
        foreach ($products as $product) {
            $quantity = rand(1, 5);
            $lineTotal = $product->price * $quantity;

            $items[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];

            $total += $lineTotal;
        }

        OrderItem::insert($items);
        $order->update(['total' => $total]);
    });
}
}

