@extends('components.dashboard')

@section('content')
@php
    $dashboardRoute = match(Auth::user()->category) {
        'systemadmin' => route('dashboard.systemadmin'),
        'supplier' => route('dashboard.supplier'),
        'wholesaler' => route('dashboard.wholesaler'),
        'customer' => route('dashboard.customer'),
        'staff' => route('dashboard.staff'),
        default => '#'
    };
@endphp

<div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 p-6 rounded shadow border border-gray-200 dark:border-gray-700">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Raw Material</h2>

    @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-4 rounded dark:bg-red-900 dark:text-red-100">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('raw_materials.update', $material) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $material->name) }}"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Type</label>
            <input type="text" name="type" id="type" value="{{ old('type', $material->type) }}"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Quantity</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $material->quantity) }}"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Unit</label>
            <input type="text" name="unit" id="unit" value="{{ old('unit', $material->unit) }}"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Unit Price (UGX)</label>
            <input type="number" step="0.01" name="unit_price" id="unit_price" value="{{ old('unit_price', $material->unit_price) }}"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div class="flex justify-between">
            <a href="{{ route('raw_materials.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                <i class="fas fa-arrow-left mr-2"></i> Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded transition">
                <i class="fas fa-save mr-2"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
