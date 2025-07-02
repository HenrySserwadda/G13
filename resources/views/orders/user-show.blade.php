@extends('components.dashboard')

@section('page-title', 'Order Details')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h2>
    <div class="mb-4">
        <strong>Status:</strong> {{ $order->status }}<br>
        <strong>Placed On:</strong> {{ $order->created_at->format('Y-m-d H:i') }}
    </div>
    <h3 class="text-lg font-semibold mb-2">Order Items</h3>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Quantity</th>
                <th class="px-4 py-2">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="border px-4 py-2">{{ $item->product->name ?? 'N/A' }}</td>
                <td class="border px-4 py-2">{{ $item->quantity }}</td>
                <td class="border px-4 py-2">UGX {{ number_format($item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        <a href="{{ route('user-orders.index') }}" class="text-blue-600 hover:underline">&larr; Back to My Orders</a>
    </div>
</div>
@endsection
