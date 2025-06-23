{{-- filepath: resources/views/products/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bags Shop - Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Available Bags</h1>

        @auth
            @if(Auth::user()->category === 'systemadmin')
                <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-4 inline-block">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </a>
            @endif
        @endauth

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded shadow p-4 flex flex-col">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover mb-4 rounded">
                    @endif
                    <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-700 mb-2">{{ $product->description }}</p>
                    <div class="mb-4 font-bold">UGX {{ number_format($product->price) }}</div>

                    <div class="mt-auto flex space-x-2">
                        {{-- Add to Cart for all except systemadmin --}}
                        @auth
                            @if(Auth::user()->category !== 'systemadmin')
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded w-full">
                                        <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                                    </button>
                                </form>
                            @endif

                            {{-- Edit/Delete for systemadmin --}}
                            @if(Auth::user()->category === 'systemadmin')
                                <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500">No products found.</div>
            @endforelse
        </div>
    </div>
</body>
</html>