{{-- filepath: resources/views/products/show.blade.php --}}
@extends('components.dashboard')

@section('title', $product->name . ' - DURABAG')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Back link -->
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>

        <!-- Product Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden max-w-xl mx-auto border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
            <!-- Product Image -->
            @if($product->image)
                @if($product->is_ml_generated)
                    <img src="/{{ $product->image }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-80 object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center" style="display: none;">
                        <i class="fas fa-image text-5xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                @else
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-80 object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center" style="display: none;">
                        <i class="fas fa-image text-5xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                @endif
            @else
                <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-image text-5xl text-gray-400 dark:text-gray-500"></i>
                </div>
            @endif

            <!-- Product Details -->
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">{{ $product->name }}</h2>
                <p class="mb-4 text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                <div class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-6">
                    UGX {{ number_format($product->price) }}
                </div>

                <!-- Action Buttons -->
                @auth
                    @if(Auth::user()->category !== 'systemadmin')
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg w-full flex items-center justify-center transition-colors duration-300 shadow-md">
                                <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <i class="fas fa-info-circle mr-1"></i> 
                                You are logged in as <strong>System Admin</strong>, cart not available.
                            </p>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a> to order this product.
                        </p>
                    </div>
                @endauth

                <!-- Admin Actions -->
                @auth
                    @if(Auth::user()->category === 'systemadmin')
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex space-x-3">
                            <a href="{{ route('products.edit', $product->id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                    <i class="fas fa-trash mr-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection