
@extends('components.dashboard')
@section('page-title', 'Inventory Details')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Inventory Details</h2>
    <div class="bg-white rounded shadow p-6 max-w-lg">
        <div class="mb-4">
            <strong>Product:</strong>
            <span>{{ $inventory->product->name ?? 'N/A' }}</span>
        </div>
        <div class="mb-4">
            <strong>Quantity:</strong>
            <span>{{ $inventory->quantity }}</span>
        </div>
        <div class="mb-4">
            <strong>Stock Status:</strong>
            <span>{{ ucfirst($inventory->stock_status) }}</span>
        </div>
        <div class="mb-4">
            <strong>Last Updated:</strong>
            <span>{{ $inventory->updated_at->format('Y-m-d H:i') }}</span>
        </div>
        <a href="{{ route('wholesaler-retailer-inventory.edit', $inventory) }}" class="bg-blue-600 text-white px-3 py-1 rounded">Edit</a>
        <a href="{{ route('wholesaler-retailer-inventory.index') }}" class="ml-4 text-gray-600 hover:underline">Back to Inventory</a>
    </div>
</div>
@endsection