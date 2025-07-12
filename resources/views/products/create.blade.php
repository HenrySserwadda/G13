{{-- filepath: resources/views/products/create.blade.php --}}
@extends('components.dashboard')

@section('title', 'Add New Product - DURABAG')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Back link -->
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>

        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-center mb-8 text-gray-800">
            <i class="fas fa-plus-circle mr-2"></i>Add New Product
        </h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 max-w-lg mx-auto">
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
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" 
              class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto border border-gray-200">
            @csrf

            <!-- Product Name -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    <i class="fas fa-tag mr-2"></i>Product Name
                </label>
                <input type="text" name="name" 
                       class="w-full px-3 py-2 border rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       required value="{{ old('name') }}"
                       placeholder="Enter product name">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    <i class="fas fa-align-left mr-2"></i>Description
                </label>
                <textarea name="description" rows="4"
                          class="w-full px-3 py-2 border rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                          required
                          placeholder="Enter product description">{{ old('description') }}</textarea>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    <i class="fas fa-money-bill-wave mr-2"></i>Price (UGX)
                </label>
                <input type="number" step="0.01" name="price" 
                       class="w-full px-3 py-2 border rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       required value="{{ old('price') }}"
                       placeholder="Enter price">
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    <i class="fas fa-cubes mr-2"></i>Quantity
                </label>
                <input type="number" name="quantity" min="0"
                       class="w-full px-3 py-2 border rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('quantity', 0) }}"
                       placeholder="Enter quantity">
            </div>

            <!-- Product Image -->
            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700">
                    <i class="fas fa-image mr-2"></i>Product Image
                </label>
                <input type="file" name="image" 
                       class="w-full px-3 py-2 border rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i> Optional. Max 2MB. JPG/PNG only.
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition-colors duration-300 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </button>
            </div>
        </form>
    </div>
@endsection