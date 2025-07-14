@extends('components.dashboard')

@section('page-title', 'System Admin Dashboard')
@section('page-description', 'Overview of your system admin dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-blue-600">{{ $userCount }}</div>
            <div class="text-gray-600 mt-2">Total Users</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-green-600">{{ $activeUserCount }}</div>
            <div class="text-gray-600 mt-2">Active Users</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-purple-600">{{ $productCount }}</div>
            <div class="text-gray-600 mt-2">Products</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-yellow-600">{{ $orderCount }}</div>
            <div class="text-gray-600 mt-2">Orders</div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Recent Activities</h2>
            <ul>
                @foreach($recentActivities as $activity)
                    <li class="mb-2 flex items-center">
                        <i class="fas fa-{{ $activity->icon }} text-blue-500 mr-2"></i>
                        <span>{{ $activity->description }}</span>
                        <span class="ml-auto text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Low Stock Products</h2>
            <ul>
                @foreach($lowStockProducts as $product)
                    <li class="mb-2 flex items-center">
                        <span class="font-semibold">{{ $product->name }}</span>
                        <span class="ml-auto text-red-600">Qty: {{ $product->quantity }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Completed Orders</h2>
            <ul>
                @foreach($completedOrders as $order)
                    <li class="mb-2 flex items-center">
                        <span>Order #{{ $order->id }}</span>
                        <span class="ml-auto text-green-600">UGX {{ number_format($order->total) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Critical Raw Materials</h2>
            <ul>
                @foreach($criticalMaterials as $material)
                    <li class="mb-2 flex items-center">
                        <span class="font-semibold">{{ $material->name }}</span>
                        <span class="ml-auto text-red-600">Qty: {{ $material->quantity }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Total Revenue</h2>
        <div class="text-3xl font-bold text-green-700">UGX {{ number_format($totalRevenue) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
        <ul>
            @foreach($recentOrders as $order)
                <li class="mb-2 flex items-center">
                    <span>Order #{{ $order->id }}</span>
                    <span class="ml-auto text-blue-600">UGX {{ number_format($order->total) }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">User Registration Trend</h2>
        <canvas id="userChart" width="400" height="150"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Sales Analytics</h2>
        <canvas id="salesChart" width="400" height="150"></canvas>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Example chart rendering for user registration trend
        const userChart = document.getElementById('userChart').getContext('2d');
        new Chart(userChart, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [{
                    label: 'User Registrations',
                    data: @json($chartData['values'] ?? []),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        // Example chart rendering for sales analytics (reuse chartData if appropriate)
        const salesChart = document.getElementById('salesChart').getContext('2d');
        new Chart(salesChart, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [{
                    label: 'Sales',
                    data: @json($chartData['values'] ?? []),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection