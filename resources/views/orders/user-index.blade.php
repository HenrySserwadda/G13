@extends('components.dashboard')

@section('page-title', 'My Orders')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">My Orders</h2>
    @if($orders->count())
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="px-4 py-2">Order #</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Created At</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td class="border px-4 py-2">{{ $order->id }}</td>
                <td class="border px-4 py-2">{{ $order->status }}</td>
                <td class="border px-4 py-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('user-orders.show', $order->id) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $orders->links() }}</div>
    @else
    <p>No orders found.</p>
    @endif
</div>
@endsection
