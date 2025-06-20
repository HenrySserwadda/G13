@extends('dashboard') {{-- or your main layout file --}}

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">All Products</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-all duration-300">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg">
            @else
                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-t-lg text-gray-500">No Image</div>
            @endif
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $product->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->type }}</p>
                <p class="text-green-600 font-semibold mt-2">UGX {{ number_format($product->selling_price) }}</p>
                <p class="text-sm text-gray-500 mt-1">Stock: {{ $product->stock_quantity }}</p>
                <a href="#" class="inline-block mt-4 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg">Add to Cart</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
