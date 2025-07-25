@extends('components.dashboard')
@section('page-title', 'Dashboard')
@section('page-description', ' Business management ')

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
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-gray-600">Here's what's happening with your business today</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full">
                                <i class="fas fa-handshake text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Tiles -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Products Tile -->
            <a href="{{ route('products.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-blue-300 overflow-hidden">
                <div class="absolute inset-0 bg-blue-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 text-blue-600">
                            <i class="fas fa-box-open text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Products</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Browse our wholesale products and special offers</p>
                    <div class="text-blue-600 text-sm font-medium flex items-center">
                        <span>View Products</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>

            <!-- Orders Tile -->
            <a href="{{ route('user-orders.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-green-300 overflow-hidden">
                <div class="absolute inset-0 bg-green-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 text-green-600">
                            <i class="fas fa-clipboard-list text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Your Orders</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Manage and track your wholesale orders</p>
                    <div class="text-green-600 text-sm font-medium flex items-center">
                        <span>View Orders</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>

            <!-- Inventory Tile -->
            <a href="{{ route('wholesaler-retailer-inventory.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-purple-300 overflow-hidden">
                <div class="absolute inset-0 bg-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 text-purple-600">
                            <i class="fas fa-warehouse text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Inventory</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Monitor your current stock levels</p>
                    <div class="text-purple-600 text-sm font-medium flex items-center">
                        <span>Manage Inventory</span>
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
                    <!-- Orders Stat -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Orders</p>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</h3>
                            </div>
                            <div class="text-blue-500">
                                <i class="fas fa-shopping-cart text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Revenue Stat -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Spent</p>
                                <h3 class="text-2xl font-bold text-gray-800">UGX {{ number_format($totalSpent) }}</h3>
                            </div>
                            <div class="text-green-500">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inventory Stat -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Inventory Items</p>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $totalInventory }}</h3>
                            </div>
                            <div class="text-purple-500">
                                <i class="fas fa-boxes text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
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
    </div>
</div>
@endsection