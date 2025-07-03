
@extends('components.dashboard')
@section('page-title', 'Edit Inventory')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Edit Inventory</h2>
    <form method="POST" action="{{ route('wholesaler-retailer-inventory.update', $inventory) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold">Product</label>
            <input type="text" value="{{ $inventory->product->name ?? 'N/A' }}" class="border rounded p-2 w-full bg-gray-100" readonly>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block font-semibold">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="border rounded p-2 w-full" min="0" value="{{ $inventory->quantity }}" required>
        </div>
        <div class="mb-4">
            <label for="stock_status" class="block font-semibold">Stock Status</label>
            <select name="stock_status" id="stock_status" class="border rounded p-2 w-full">
                <option value="in_stock" {{ $inventory->stock_status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="low_stock" {{ $inventory->stock_status == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="out_of_stock" {{ $inventory->stock_status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('wholesaler-retailer-inventory.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection