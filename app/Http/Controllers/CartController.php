<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Add product to cart (for customers, retailers, wholesalers)
    public function add(Request $request, Product $product)
    {
        // You can implement your cart logic here (session or DB)
        $cart = session()->get('cart', []);
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => ($cart[$product->id]['quantity'] ?? 0) + 1,
        ];
        session(['cart' => $cart]);
        return back()->with('success', 'Product added to cart!');
    }
}
