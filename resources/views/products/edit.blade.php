<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold text-center mb-8">Edit Product</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 max-w-lg mx-auto">
                <strong class="font-bold">Whoops!</strong>
                <span class="block">There were some problems with your input:</span>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md max-w-lg mx-auto">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-700">Product Name</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2"
                       required value="{{ old('name', $product->name) }}">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded px-3 py-2"
                          required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-700">Price (UGX)</label>
                <input type="number" step="0.01" name="price" class="w-full border border-gray-300 rounded px-3 py-2"
                       required value="{{ old('price', $product->price) }}">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-700">Product Image</label>
                <input type="file" name="image" class="w-full border border-gray-300 rounded px-3 py-2">
                @if ($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-32 mt-2 rounded shadow">
                @endif
            </div>

            <div class="text-center">
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded font-semibold">
                    Update Product
                </button>
            </div>
        </form>
    </div>

</body>
</html>
