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
            'user_id' => Auth::id(), // Use numeric ID
            'location' => $request->location,
            'mobile' => $request->mobile,
            'total' => array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)),
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

        session()->forget('cart');
        return redirect()->route('products.index')->with('success', 'Order placed!');
    }
} 