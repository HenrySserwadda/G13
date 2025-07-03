
@extends('components.dashboard')
@section('page-title', 'Order Details')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h2>
    <div class="mb-4">
        <strong>User:</strong> {{ $order->user->name ?? 'N/A' }}<br>
        <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
        <strong>Location:</strong> {{ $order->location ?? 'N/A' }}<br>
        <strong>Mobile:</strong> {{ $order->mobile ?? 'N/A' }}<br>
        <strong>Placed On:</strong> {{ $order->created_at->format('Y-m-d H:i') }}
    </div>
    <h3 class="text-lg font-semibold mb-2">Order Items</h3>
    <table class="min-w-full bg-white border mb-4">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>UGX {{ number_format($item->price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="mb-4">
        <strong>Total to Pay:</strong> <span class="text-green-600">UGX {{ number_format($order->total ?? $order->items->sum(fn($item) => $item->price * $item->quantity), 2) }}</span>
    </div>
    <form method="POST" action="{{ route('orders.manage.updateStatus', $order->id) }}">
        @csrf
        <label for="status" class="font-semibold">Change Status:</label>
        <select name="status" id="status" class="border rounded p-1 mx-2">
            @foreach(['pending', 'approved', 'rejected', 'completed'] as $status)
                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Update</button>
    </form>
    <a href="{{ route('orders.manage.index') }}" class="text-blue-600 hover:underline mt-4 inline-block">&larr; Back to Orders</a>
</div>
@endsection