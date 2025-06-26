{{-- filepath: resources/views/products/index.blade.php --}}
@extends('components.dashboard')

@section('title', 'Products - DURABAG')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 300px; /* Increased height for better image display */
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.05); /* Subtle zoom effect on hover */
        }
        .no-image-placeholder {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }
        .dark .no-image-placeholder {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-shopping-bag mr-2"></i> Our Bag Collection
            </h1>

            @auth
                @if(Auth::user()->category === 'systemadmin')
                    <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center shadow-lg transition-all duration-300 hover:shadow-xl">
                        <i class="fas fa-plus-circle mr-2"></i> Add New Product
                    </a>
                @endif
            @endauth
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 dark:bg-green-900 dark:border-green-700 dark:text-green-100">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="product-card bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl">
                    <div class="product-image-container">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image"
                                 loading="lazy" <!-- Lazy loading for better performance -->
                                 srcset="{{ asset('storage/'.$product->image) }} 1x, 
                                         {{ asset('storage/'.$product->image) }} 2x"> <!-- For high DPI displays -->
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-camera text-5xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-5">
                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $product->name }}</h2>
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">UGX {{ number_format($product->price) }}</div>
                        </div>
                        
                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $product->description }}</p>

                        <div class="flex space-x-3">
                            <a href="{{ route('products.show', $product->id) }}" 
                               class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300 flex-1 justify-center">
                                <i class="fas fa-eye mr-2"></i> View
                            </a>

                            @auth
                                @if(Auth::user()->category !== 'systemadmin')
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full flex items-center justify-center transition-colors duration-300">
                                            <i class="fas fa-cart-plus mr-2"></i> Cart
                                        </button>
                                    </form>
                                @endif

                                @if(Auth::user()->category === 'systemadmin')
                                    <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors duration-300 flex-1">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-500 dark:text-gray-400 text-xl">
                        <i class="fas fa-box-open text-5xl mb-4"></i>
                        <p class="text-2xl font-light">No products available yet</p>
                        @auth
                            @if(Auth::user()->category === 'systemadmin')
                                <a href="{{ route('products.create') }}" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg">
                                    <i class="fas fa-plus-circle mr-2"></i> Create First Product
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection