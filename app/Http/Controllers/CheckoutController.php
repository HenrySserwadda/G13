<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.show');
        return view('checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.show');

        $request->validate([
            'location' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
        ]);

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Check stock
        foreach ($cart as $id => $item) {
            $available = $products[$id]->quantity ?? 0;
            if ($item['quantity'] > $available) {
                return back()->with('error', "Not enough stock for {$item['name']}.");
            }
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::user()->user_id, 
            'location' => $request->location,
            'mobile' => $request->mobile,
            'total' => array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)),
            'supply_center_id' => \App\Models\SupplyCenter::inRandomOrder()->first()->id,
        ]);

        // Create order items and deduct stock
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            $products[$id]->decrement('quantity', $item['quantity']);
        }

        // If order is automatically completed, process to sales
        if ($order->status === 'completed') {
            $this->processOrderToSales($order);
        }

        session()->forget('cart');
        return redirect()->route('products.index')->with('success', 'Order placed!');
    }

    private function processOrderToSales($order)
    {
        if (!$order->supply_center_id) {
            return; // Skip if no supply center assigned
        }

        try {
            $salesMonth = $order->created_at->format('Y-m');
            $supplyCenterId = $order->supply_center_id;

            // Check if sales record already exists for this supply center and month
            $existingSale = \App\Models\Sale::where('supply_center_id', $supplyCenterId)
                                           ->where('sales_month', $salesMonth . '-01')
                                           ->first();

            if ($existingSale) {
                // Update existing sales record
                $existingSale->monthly_sales += $order->total;
                $existingSale->save();
            } else {
                // Create new sales record
                \App\Models\Sale::create([
                    'supply_center_id' => $supplyCenterId,
                    'monthly_sales' => $order->total,
                    'sales_month' => $salesMonth . '-01',
                    'order_id' => $order->id,
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the order creation
            \Log::error("Error processing order {$order->id} to sales: " . $e->getMessage());
        }
    }
} 