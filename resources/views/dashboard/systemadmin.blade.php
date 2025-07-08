@extends('components.dashboard')

@section('page-title', 'System Admin Dashboard')
@section('page-description', 'Overview of your system admin dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    @php 
        $adminName = Auth::user()->name ?? 'Admin';
        $currentDate = now()->format('l, F j, Y');
    @endphp

    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-1">Welcome back, {{ $adminName }}!</h1>
                <p class="text-gray-600">{{ $currentDate }}</p>
            </div>
            
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Key Metrics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Users Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Users</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $userCount }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $activeUserCount }} active</p>
                    </div>
                </div>
            </div>

            <!-- Products Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-box-open text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Active Products</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $productCount }}</h3>
                       
                    </div>
                </div>
            </div>

            <!-- Orders Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Orders</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $orderCount }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $completedOrders->count() }} completed</p>
                    </div>
                </div>
            </div>

            <!-- Sales Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Revenue</p>
                        <h3 class="text-2xl font-bold mt-1">UGX {{ number_format($totalRevenue, 0) }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Last 30 days</p>
                    </div>
                </div>
            </div>

            <!-- Raw Materials Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-cubes text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Raw Materials</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $rawMaterialCount }}</h3>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('dashboard.systemadmin.all-users') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-blue-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <h3 class="font-medium">Manage Users</h3>
            </a>
            <a href="{{ route('products.index') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-green-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-boxes text-green-600"></i>
                </div>
                <h3 class="font-medium">Products</h3>
            </a>
            <a href="{{ route('orders.manage.index') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-purple-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-receipt text-purple-600"></i>
                </div>
                <h3 class="font-medium">Orders</h3>
            </a>
            <a href="{{ route('reports.sales') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-yellow-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-chart-line text-yellow-600"></i>
                </div>
                <h3 class="font-medium">Sales Reports</h3>
            </a>
        </div>
    </div>

    <!-- Recent Activity & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Activity</h2>
                <a href="{{ route('dashboard.systemadmin.activity-log') }}" class="text-blue-600 text-sm hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-4">
                @if($recentActivities->count() > 0)
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start">
                        <div class="bg-gray-100 p-2 rounded-full mr-3">
                            <i class="fas fa-{{ $activity->icon }} text-gray-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-xs font-semibold">
                                    <i class="fas fa-user-circle mr-1"></i>
                                    {{ $activity->causer->name ?? '' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500">No recent activity to display</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
                <a href="{{ route('orders.manage.index') }}" class="text-blue-600 text-sm hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-4">
                @if($recentOrders->count() > 0)
                    @foreach($recentOrders as $order)
                    <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">Order #{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">Worth: UGX {{ number_format($order->total, 0) }}</p>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500">No recent orders to display</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">User Management</h2>
            <a href="{{ route('dashboard.systemadmin.all-users') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-users mr-1"></i> View All Users
            </a>
        </div>
      
      
        </div>
        
    </div>
@endsection