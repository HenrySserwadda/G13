
@extends('components.dashboard')
@section('page-title', 'Manage Orders')
@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Sales Orders</h2>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th>Order #</th>
                <th>User</th>
                <th>Status</th>
                <th>Location</th>
                <th>Mobile</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'N/A' }} ({{ $order->user->category ?? '' }})</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ $order->location }}</td>
                <td>{{ $order->mobile }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('orders.manage.show', $order->id) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection