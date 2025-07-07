@extends('components.dashboard')
@section('page-title', 'Inventory Details')
@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Inventory Details</h2>
            <a href="{{ route('wholesaler-retailer-inventory.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Inventory
            </a>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm font-medium text-gray-600">Product:</span>
                <span class="text-gray-800">{{ $inventory->product->name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm font-medium text-gray-600">Quantity:</span>
                <span class="text-gray-800">{{ $inventory->quantity }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm font-medium text-gray-600">Stock Status:</span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    @if($inventory->stock_status === 'in_stock') bg-green-100 text-green-800
                    @elseif($inventory->stock_status === 'low_stock') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($inventory->stock_status) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Last Updated:</span>
                <span class="text-gray-800">{{ $inventory->updated_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 mt-6 border-t">
            <a href="{{ route('wholesaler-retailer-inventory.edit', $inventory) }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow">
                Edit Inventory
            </a>
        </div>
    </div>
</div>
@endsection