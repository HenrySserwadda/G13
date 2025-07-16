@extends('components.dashboard')

@section('page-title', 'System Admin Dashboard')
@section('page-description', 'Overview of your system admin dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100/20">
    <div class="container mx-auto px-4 py-8">
        <!-- Welcome Header Section -->
        <div class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/20 rounded-full transform translate-x-16 -translate-y-16"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->name ?? 'Admin' }}!</h1>
                            <p class="text-gray-600">Here's an overview of your system admin dashboard.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full">
                                <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quick Access Tiles -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <!-- Users Tile -->
            <a href="{{ route('dashboard.systemadmin.all-users') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-blue-300 overflow-hidden">
                <div class="absolute inset-0 bg-blue-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 text-blue-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Users</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Manage all users in the system</p>
                    <div class="text-blue-600 text-sm font-medium flex items-center">
                        <span>View Users</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>
            <!-- Products Tile -->
            <a href="{{ route('products.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-purple-300 overflow-hidden">
                <div class="absolute inset-0 bg-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 text-purple-600">
                            <i class="fas fa-box-open text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Products</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">View and manage all products</p>
                    <div class="text-purple-600 text-sm font-medium flex items-center">
                        <span>View Products</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>
            <!-- Orders Tile -->
            <a href="{{ route('orders.manage.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-green-300 overflow-hidden">
                <div class="absolute inset-0 bg-green-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 text-green-600">
                            <i class="fas fa-clipboard-list text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Orders</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Review and track all orders</p>
                    <div class="text-green-600 text-sm font-medium flex items-center">
                        <span>View Orders</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>
            <!-- Inventory Tile -->
            <a href="{{ route('inventories.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-yellow-300 overflow-hidden">
                <div class="absolute inset-0 bg-yellow-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4 text-yellow-600">
                            <i class="fas fa-warehouse text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Inventory</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Manage and monitor inventory</p>
                    <div class="text-yellow-600 text-sm font-medium flex items-center">
                        <span>Manage Inventory</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>
            <!-- Workers Management Tile -->
            <a href="{{ route('workforce.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-cyan-300 overflow-hidden">
                <div class="absolute inset-0 bg-cyan-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center mr-4 text-cyan-600">
                            <i class="fas fa-users-cog text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Workers</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Manage all workers and allocations</p>
                    <div class="text-cyan-600 text-sm font-medium flex items-center">
                        <span>Manage Workers</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- Business Overview Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-12">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Business Overview</h2>
                <p class="text-gray-600 text-sm">Key metrics at a glance</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Users Stat -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Users</p>
                            <h3 class="text-2xl font-bold text-blue-600">{{ $userCount }}</h3>
                        </div>
                        <div class="text-blue-500">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                    <!-- Products Stat -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Products</p>
                            <h3 class="text-2xl font-bold text-purple-600">{{ $productCount }}</h3>
                        </div>
                        <div class="text-purple-500">
                            <i class="fas fa-box-open text-2xl"></i>
                        </div>
                    </div>
                    <!-- Active Users Stat -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Active Users</p>
                            <h3 class="text-2xl font-bold text-green-600">{{ $activeUserCount }}</h3>
                        </div>
                        <div class="text-green-500">
                            <i class="fas fa-user-check text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Revenue Stat -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-12 p-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold mb-2">Total Revenue</h2>
                <div class="text-3xl font-bold text-green-700">UGX {{ number_format($totalRevenue) }}</div>
            </div>
            <div class="text-green-500">
                <i class="fas fa-money-bill-wave text-3xl"></i>
            </div>
        </div>
        <!-- Recent Activity Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-12">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Recent Activity</h2>
                <p class="text-gray-600 text-sm">Your latest transactions and updates</p>
            </div>
            <div class="p-6">
                @if($recentOrders->count())
                    <ul class="divide-y divide-gray-200">
                        @foreach($recentOrders as $order)
                            <li class="py-4 flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        Order #{{ $order->id }}
                                    </div>
                                    <div class="text-gray-500 text-sm">
                                        {{ ucfirst($order->status) }} &middot; {{ $order->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                                <div class="text-blue-600 font-bold">
                                    UGX {{ number_format($order->total) }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clock text-gray-400 text-xl"></i>
                        </div>
                        <h4 class="text-gray-500 font-medium">No recent activity yet</h4>
                        <p class="text-gray-400 text-sm mt-1">As you make orders and manage inventory, we'll show updates here</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- Analytics Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 p-6">
                <h2 class="text-xl font-bold mb-4">User Registration Trend</h2>
                <canvas id="userChart" width="400" height="150"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 p-6">
                <h2 class="text-xl font-bold mb-4">Sales Analytics</h2>
                <canvas id="salesChart" width="400" height="150"></canvas>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Registration Trend Chart
    const userChartCtx = document.getElementById('userChart').getContext('2d');
    const userRegData = @json($userRegData);
    const userRegLabels = Object.keys(userRegData);
    const userRegCounts = Object.values(userRegData);
    new Chart(userChartCtx, {
        type: 'bar',
        data: {
            labels: userRegLabels,
            datasets: [{
                label: 'Registrations',
                data: userRegCounts,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'User Registration Trend' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Sales Analytics Chart
    const salesChartCtx = document.getElementById('salesChart').getContext('2d');
    fetch('/api/sales-by-month')
        .then(res => res.json())
        .then(salesData => {
            const salesLabels = Object.keys(salesData);
            const salesTotals = Object.values(salesData);
            new Chart(salesChartCtx, {
                type: 'line',
                data: {
                    labels: salesLabels,
                    datasets: [{
                        label: 'Sales (UGX)',
                        data: salesTotals,
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'Sales Analytics' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
});
</script>
@endpush
@endsection