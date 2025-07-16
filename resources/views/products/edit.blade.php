{{-- filepath: resources/views/products/edit.blade.php --}}
@extends('components.dashboard')

@section('title', 'Edit Product - DURABAG')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Back link -->
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>

        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-white">Edit Product</h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 dark:bg-red-900 dark:border-red-700 dark:text-red-100 max-w-lg mx-auto">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <strong>Please fix these errors:</strong>
                </div>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" 
              class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 max-w-lg mx-auto border border-gray-200 dark:border-gray-700">
            @csrf
            @method('PUT')

            <!-- Product Name -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-tag mr-2"></i>Product Name
                </label>
                <input type="text" name="name" 
                       class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                       required value="{{ old('name', $product->name) }}">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-align-left mr-2"></i>Description
                </label>
                <textarea name="description" rows="4" 
                          class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                          required>{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-money-bill-wave mr-2"></i>Price (UGX)
                </label>
                <input type="number" step="0.01" name="price" 
                       class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                       required value="{{ old('price', $product->price) }}">
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-cubes mr-2"></i>Quantity
                </label>
                <input type="number" name="quantity" min="0"
                       class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('quantity', $product->quantity ?? 0) }}"
                       placeholder="Enter quantity">
            </div>

            <!-- Style -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-paint-brush mr-2"></i>Style
                </label>
                <select name="style" class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Style</option>
                    <option value="Tote" {{ old('style', $product->style) == 'Tote' ? 'selected' : '' }}>Tote</option>
                    <option value="Messenger" {{ old('style', $product->style) == 'Messenger' ? 'selected' : '' }}>Messenger</option>
                    <option value="Backpack" {{ old('style', $product->style) == 'Backpack' ? 'selected' : '' }}>Backpack</option>
                </select>
            </div>

            <!-- Color -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-palette mr-2"></i>Color
                </label>
                <select name="color" class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Color</option>
                    <option value="Red" {{ old('color', $product->color) == 'Red' ? 'selected' : '' }}>Red</option>
                    <option value="Pink" {{ old('color', $product->color) == 'Pink' ? 'selected' : '' }}>Pink</option>
                    <option value="Black" {{ old('color', $product->color) == 'Black' ? 'selected' : '' }}>Black</option>
                    <option value="Green" {{ old('color', $product->color) == 'Green' ? 'selected' : '' }}>Green</option>
                    <option value="Blue" {{ old('color', $product->color) == 'Blue' ? 'selected' : '' }}>Blue</option>
                    <option value="Gray" {{ old('color', $product->color) == 'Gray' ? 'selected' : '' }}>Gray</option>
                </select>
            </div>

            <!-- Gender -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-venus-mars mr-2"></i>Gender
                </label>
                <select name="gender" class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $product->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $product->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="unisex" {{ old('gender', $product->gender) == 'unisex' ? 'selected' : '' }}>Unisex</option>
                </select>
            </div>

            <!-- Product Image -->
            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-image mr-2"></i>Product Image
                </label>
                <input type="file" name="image" 
                       class="w-full px-3 py-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                
                @if ($product->image)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Current Image:</p>
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" 
                             class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                    </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg shadow transition-colors duration-300 flex items-center">
                    <i class="fas fa-save mr-2"></i> Update Product
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush