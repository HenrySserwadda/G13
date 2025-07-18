<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Show all products to any authenticated user
    public function index()
    {
        $products = Product::latest()->paginate(12);
        return view('products.index', compact('products'));
    }

    // Show a single product (optional)
    public function show(Product $product)
    {
        // Find related products by style, color, and gender (excluding the current product)
        $query = Product::where('id', '!=', $product->id)
            ->where(function($q) use ($product) {
                $q->where('ml_style', $product->ml_style)
                  ->orWhere('ml_color', $product->ml_color)
                  ->orWhere('ml_gender', $product->ml_gender)
                  ->orWhere('style', $product->style)
                  ->orWhere('color', $product->color)
                  ->orWhere('gender', $product->gender);
            });
        $recommendations = $query->inRandomOrder()->limit(8)->get()->toArray();

        // If not enough, fill with random products (excluding current and already selected)
        if (count($recommendations) < 4) {
            $excludeIds = array_merge([$product->id], array_column($recommendations, 'id'));
            $more = Product::whereNotIn('id', $excludeIds)
                ->inRandomOrder()->limit(8 - count($recommendations))->get()->toArray();
            $recommendations = array_merge($recommendations, $more);
        }

        return view('products.show', compact('product', 'recommendations'));
    }

    // Only systemadmin can access create form
    public function create()
    {
        if (Auth::user()->category !== 'systemadmin') {
            abort(403);
        }
        return view('products.create');
    }

    // Only systemadmin can store new product
    public function store(Request $request)
    {
        if (Auth::user()->category !== 'systemadmin') {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'quantity' => 'nullable|integer|min:0',
            'style' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,unisex',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        if (isset($validated['style'])) {
            $validated['style'] = ucfirst(strtolower($validated['style']));
        }
        if (isset($validated['color'])) {
            $validated['color'] = ucfirst(strtolower($validated['color']));
        }
        if (isset($validated['gender'])) {
            $validated['gender'] = ucfirst(strtolower($validated['gender']));
        }
        
        // Assign a random supply center to the product
        $validated['supply_center_id'] = \App\Models\SupplyCenter::inRandomOrder()->first()->id;
        
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product added!');
    }

    // Only systemadmin can edit
    public function edit(Product $product)
    {
        if (Auth::user()->category !== 'systemadmin') {
            abort(403);
        }
        return view('products.edit', compact('product'));
    }

    // Only systemadmin can update
    public function update(Request $request, Product $product)
    {
        if (Auth::user()->category !== 'systemadmin') {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'quantity' => 'nullable|integer|min:0',
            'style' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,unisex',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        if (isset($validated['style'])) {
            $validated['style'] = ucfirst(strtolower($validated['style']));
        }
        if (isset($validated['color'])) {
            $validated['color'] = ucfirst(strtolower($validated['color']));
        }
        if (isset($validated['gender'])) {
            $validated['gender'] = ucfirst(strtolower($validated['gender']));
        }
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated!');
    }

    // Update product quantity (for staff and systemadmin)
    public function updateQuantity(Request $request, Product $product)
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update(['quantity' => $request->quantity]);
        return redirect()->back()->with('success', 'Product quantity updated!');
    }

    // Initiate remake for a product
    public function initiateRemake(Request $request, Product $product)
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'remake_quantity' => 'required|integer|min:1',
        ]);

        $product->update([
            'remake_quantity' => $request->remake_quantity,
            'remake_status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Remake initiated for ' . $product->name);
    }

    // Update remake status
    public function updateRemakeStatus(Request $request, Product $product)
    {
        if (!in_array(Auth::user()->category, ['systemadmin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'remake_status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $product->update(['remake_status' => $request->remake_status]);

        // If remake is completed, add the remake quantity to the product quantity
        if ($request->remake_status === 'completed') {
            $product->increment('quantity', $product->remake_quantity);
            $product->update(['remake_quantity' => 0]);
        }

        return redirect()->back()->with('success', 'Remake status updated!');
    }

    // Only systemadmin can delete
    public function destroy(Product $product)
    {
        if (Auth::user()->category !== 'systemadmin') {
            abort(403);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }
}
