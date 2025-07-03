
@extends('components.dashboard')
@section('page-title', 'Add Inventory')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Add Inventory</h2>
    <form method="POST" action="{{ route('wholesaler-retailer-inventory.store') }}">
        @csrf
        <div class="mb-4">
            <label for="product_id" class="block font-semibold">Product</label>
            <select name="product_id" id="product_id" class="border rounded p-2 w-full" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block font-semibold">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="border rounded p-2 w-full" min="0" required>
        </div>
        <div class="mb-4">
            <label for="stock_status" class="block font-semibold">Stock Status</label>
            <select name="stock_status" id="stock_status" class="border rounded p-2 w-full">
                <option value="in_stock">In Stock</option>
                <option value="low_stock">Low Stock</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
        <a href="{{ route('wholesaler-retailer-inventory.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection