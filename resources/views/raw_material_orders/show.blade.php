@extends('components.dashboard')
@section('page-title', 'Order Details')
@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
            <a href="{{ route('raw-material-orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Orders
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Order Information</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Supplier:</span>
                        <p class="text-gray-800">{{ $order->supplier->name ?? 'N/A' }} ({{ $order->supplier->user_id ?? '' }})</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Placed On:</span>
                        <p class="text-gray-800">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Items:</span>
                        <span class="text-gray-800">{{ count($order->items) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Total Quantity:</span>
                        <span class="text-gray-800">{{ $order->items->sum('quantity') }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="text-sm font-medium text-gray-600">Total Amount:</span>
                        @php $orderTotal = 0; @endphp
                        @foreach($order->items as $item)
                            @php $orderTotal += $item->quantity * $item->price; @endphp
                        @endforeach
                        <span class="font-bold text-blue-600">{{ number_format($orderTotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raw Material</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->rawMaterial->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Total:</td>
                        <td class="px-6 py-3 text-sm font-bold text-blue-600">{{ number_format($orderTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection