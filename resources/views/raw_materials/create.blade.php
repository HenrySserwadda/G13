@extends('components.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Back link -->
        <div class="mb-6">
            <a href="{{ route('raw_materials.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Raw Materials
            </a>
        </div>

        <!-- Page Header -->
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
            <i class="fas fa-plus-circle mr-2"></i>Add New Raw Material
        </h2>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 dark:bg-red-900 dark:border-red-700 dark:text-red-100">
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
        <form action="{{ route('raw_materials.store') }}" method="POST" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
            @csrf
            
            <!-- Material Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <i class="fas fa-tag mr-2"></i>Material Name
                </label>
                <input type="text" name="name" id="name" 
                       class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <i class="fas fa-list-alt mr-2"></i>Type
                </label>
                <input type="text" name="type" id="type" 
                       class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <i class="fas fa-boxes mr-2"></i>Quantity
                </label>
                <input type="number" name="quantity" id="quantity" 
                       class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>

            <!-- Unit -->
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <i class="fas fa-ruler mr-2"></i>Unit
                </label>
                <select name="unit" id="unit"
                        class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="pcs">pcs</option>
                    <option value="meters">meters</option>
                </select>
            </div>

            <!-- Unit Price -->
            <div>
                <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <i class="fas fa-money-bill-wave mr-2"></i>Unit Price (UGX)
                </label>
                <input type="number" name="unit_price" step="0.01" id="unit_price" 
                       class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('raw_materials.index') }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition-colors duration-300">
                    <i class="fas fa-save mr-2"></i> Save Material
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush