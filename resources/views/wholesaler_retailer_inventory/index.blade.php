
@extends('components.dashboard')
@section('page-title', 'My Inventory')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">My Inventory</h2>
    <a href="{{ route('wholesaler-retailer-inventory.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded mb-4 inline-block">Add Product</a>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Stock Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($inventories as $inv)
            <tr>
                <td>{{ $inv->product->name ?? 'N/A' }}</td>
                <td>{{ $inv->quantity }}</td>
                <td>{{ ucfirst($inv->stock_status) }}</td>
                <td>
                    <a href="{{ route('wholesaler-retailer-inventory.edit', $inv) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('wholesaler-retailer-inventory.destroy', $inv) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection