@extends('dashboard') {{-- or your main layout file --}}

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Add New Product</h2>
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
            <input type="text" name="name" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
            <input type="text" name="type" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selling Price (UGX)</label>
            <input type="number" name="selling_price" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Quantity</label>
            <input type="number" name="stock_quantity" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Image</label>
            <input type="file" name="image" class="mt-1 block w-full text-gray-700 dark:text-white">
        </div>

        <div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">Save Product</button>
        </div>
    </form>
</div>
@endsection
