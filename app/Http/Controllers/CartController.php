<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Add product to cart
    public function add(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $newQty = $currentQty + 1;

        if ($newQty > $product->quantity) {
            return back()->with('error', "Only {$product->quantity} available for {$product->name}.");
        }

        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $newQty,
        ];
        session(['cart' => $cart]);
        return back()->with('success', 'Product added to cart!');
    }

    // Show cart page
    public function show()
    {
        $cart = session('cart', []);
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        return view('cart.index', compact('cart', 'products'));
    }

    // Update quantity
    public function update(Request $request, $productId)
    {
        $cart = session('cart', []);
        $product = Product::findOrFail($productId);

        $requestedQty = max(1, (int)$request->input('quantity', 1));
        $availableQty = $product->quantity ?? 0;

        if ($requestedQty > $availableQty) {
            return back()->with('error', "Only $availableQty available for {$product->name}.");
        }

        $cart[$productId]['quantity'] = $requestedQty;
        session(['cart' => $cart]);
        return back();
    }

    // Remove item
    public function remove($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
        return back();
    }
}
