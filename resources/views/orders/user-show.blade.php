@extends('components.dashboard')
@section('page-title', 'My Orders')
@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                @php
                    $user = \App\Models\User::where('id', $order->user_id)->orWhere('user_id', $order->user_id)->first();
                @endphp
                <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
                <p class="text-sm text-gray-600 mt-1">Status: <span class="font-semibold">{{ ucfirst($order->status) }}</span></p>
                <p class="text-sm text-gray-600 mt-1">Placed on: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                <p class="text-sm text-gray-600 mt-1">Customer: <span class="font-semibold">{{ $user->name ?? 'N/A' }}</span></p>
                <p class="text-sm text-gray-600 mt-1">Account Type: <span class="font-semibold">{{ $user->category ?? 'N/A' }}</span></p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">UGX {{ number_format($item->price) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">UGX {{ number_format($item->quantity * $item->price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-right">
            <span class="text-lg font-bold">Total: UGX {{ number_format($order->items->sum(function($item) { return $item->quantity * $item->price; })) }}</span>
        </div>
    </div>
</div>
@endsection