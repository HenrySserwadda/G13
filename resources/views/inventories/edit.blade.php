@extends('components.dashboard')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Inventory Entry</h2>
</div>

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900 dark:text-red-200">
        <strong class="font-bold">Error:</strong> Please fix the following issues:
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('inventories.update', $inventory->id) }}" method="POST" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded shadow border dark:border-gray-700">
    @csrf
    @method('PUT')

    <!-- Raw Material (readonly) -->
    <div>
        <label for="raw_material_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Raw Material</label>
        <input type="text" disabled value="{{ $inventory->rawMaterial->name ?? 'N/A' }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md shadow-sm">
    </div>

    <!-- Quantities -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="on_hand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity On Hand</label>
            <input type="number" name="on_hand" id="on_hand" min="0" value="{{ old('on_hand', $inventory->on_hand) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="on_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity On Order</label>
            <input type="number" name="on_order" id="on_order" min="0" value="{{ old('on_order', $inventory->on_order) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Status Dropdowns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="stock_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Status</label>
            <select name="stock_status" id="stock_status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach (['in_stock' => 'In Stock', 'low' => 'Low', 'out' => 'Out'] as $key => $label)
                    <option value="{{ $key }}" {{ old('stock_status', $inventory->stock_status) === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="delivery_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Status</label>
            <select name="delivery_status" id="delivery_status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach (['received', 'in_transit', 'need_to_order', 'in_progress'] as $status)
                    <option value="{{ $status }}" {{ old('delivery_status', $inventory->delivery_status) === $status ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Dates -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="delivered_on" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivered On</label>
            <input type="date" name="delivered_on" id="delivered_on" value="{{ old('delivered_on', $inventory->delivered_on) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="expected_delivery" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Delivery</label>
            <input type="date" name="expected_delivery" id="expected_delivery" value="{{ old('expected_delivery', $inventory->expected_delivery) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
            <i class="fas fa-save mr-2"></i>Update Entry
        </button>
        <a href="{{ route('inventories.index') }}" class="ml-4 text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
    </div>
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
