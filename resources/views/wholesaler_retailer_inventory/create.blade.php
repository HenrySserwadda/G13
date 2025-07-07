@extends('components.dashboard')
@section('page-title', 'Add Inventory')
@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Add Inventory</h2>
            <a href="{{ route('wholesaler-retailer-inventory.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Inventory
            </a>
        </div>

        <form method="POST" action="{{ route('wholesaler-retailer-inventory.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <select name="product_id" id="product_id" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="quantity" id="quantity" min="0" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                <select name="stock_status" id="stock_status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('wholesaler-retailer-inventory.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm">
                    Add Inventory
                </button>
            </div>
        </form>
    </div>
</div>
@endsection