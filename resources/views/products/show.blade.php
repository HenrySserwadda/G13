<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details - {{ $product->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto px-4 py-10">
        <div class="bg-white rounded shadow p-6 max-w-xl mx-auto">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover mb-4 rounded">
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded mb-4">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-2 text-gray-800">{{ $product->name }}</h2>
            <p class="mb-2 text-gray-600">{{ $product->description }}</p>
            <div class="mb-4 text-lg font-bold text-blue-700">UGX {{ number_format($product->price) }}</div>

            @auth
                @if(Auth::user()->category !== 'systemadmin')
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">You are logged in as <strong>System Admin</strong>, cart not available.</p>
                @endif
            @else
                <div class="text-sm mt-4">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to order this product.
                </div>
            @endauth
        </div>
    </div>

</body>
</html>
