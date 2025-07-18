<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\MLProductService;

class CartController extends Controller
{
    protected $mlProductService;

    public function __construct(MLProductService $mlProductService)
    {
        $this->mlProductService = $mlProductService;
    }

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
            'is_ml_generated' => $product->is_ml_generated,
        ];
        session(['cart' => $cart]);
        
        // If it's an ML product, increment its popularity score
        if ($product->isMLGenerated()) {
            $this->mlProductService->incrementPopularity($product->id);
        }
        
        if ($request->expectsJson() || $request->ajax()) {
            $cart = session('cart', []);
            $count = array_sum(array_column($cart, 'quantity'));
            return response()->json(['success' => 'Product added to cart!', 'count' => $count]);
        }
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
