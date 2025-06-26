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
        return view('products.show', compact('product'));
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
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
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
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated!');
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
