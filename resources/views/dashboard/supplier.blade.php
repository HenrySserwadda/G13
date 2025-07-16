@extends('components.dashboard')

@section('page-title', 'Supplier Dashboard')
@section('page-description', 'Overview of your supplier dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-100/20">
        <div class="container mx-auto px-4 py-8">
            <!-- Welcome Header Section -->
            <div class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100/20 rounded-full transform translate-x-16 -translate-y-16"></div>
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->name ?? 'Supplier' }}!</h1>
                                <p class="text-gray-600">Here you can manage your raw materials and inventory.</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full">
                                    <i class="fas fa-truck-loading text-purple-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Tiles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Raw Materials Tile -->
                <a href="{{ route('raw_materials.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-purple-300 overflow-hidden">
                    <div class="absolute inset-0 bg-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                    <div class="relative z-10 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 text-purple-600">
                                <i class="fas fa-cubes text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800">Raw Materials</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Manage your supplied raw materials</p>
                        <div class="text-purple-600 text-sm font-medium flex items-center">
                            <span>View Raw Materials</span>
                            <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                        </div>
                    </div>
                </a>

                
              
                <!-- Orders Tile -->
                <a href="{{ route('raw-material-orders.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-green-300 overflow-hidden">
                    <div class="absolute inset-0 bg-green-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                    <div class="relative z-10 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 text-green-600">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800">Orders</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">View and manage your supply orders</p>
                        <div class="text-green-600 text-sm font-medium flex items-center">
                            <span>View Orders</span>
                            <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Quick Actions Row -->
            <div class="flex flex-wrap gap-4 mb-10">
                <a href="{{ route('raw_materials.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow transition duration-150">
                    <i class="fas fa-plus mr-2"></i> Add Raw Material
                </a>
                <a href="{{ route('raw-material-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition duration-150">
                    <i class="fas fa-clipboard-list mr-2"></i> Place Order
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
                        <!-- Raw Materials Stat -->
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Raw Materials</p>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $rawMaterialCount }}</h3>
                            </div>
                            <div class="text-purple-500">
                                <i class="fas fa-cubes text-2xl"></i>
                            </div>
                        </div>
                        <!-- Low Stock Stat -->
                        <div class="bg-yellow-50 p-5 rounded-lg border border-yellow-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-700 mb-1 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i>Low Stock</p>
                                <h3 class="text-2xl font-bold text-yellow-800">{{ $inventoryCount }}</h3>
                            </div>
                            <div class="text-yellow-500">
                                <i class="fas fa-warehouse text-2xl"></i>
                            </div>
                        </div>
                        <!-- Orders Stat -->
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Orders</p>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $orderCount }}</h3>
                            </div>
                            <div class="text-green-500">
                                <i class="fas fa-clipboard-list text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            </div>
            <!-- Recent Activity Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-12">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
                    <p class="text-gray-600 text-sm">Your latest supply orders</p>
                </div>
                <div class="p-6">
                    @php
                        $recentOrders = \App\Models\RawMaterialOrder::where('supplier_user_id', Auth::id())
                            ->orderBy('created_at', 'desc')->take(5)->get();
                    @endphp
                    @if($recentOrders->count())
                        <ul>
                            @foreach($recentOrders as $order)
                                <li class="mb-3 flex items-center justify-between">
                                    <span>Order #{{ $order->id }} - <span class="text-gray-500 text-xs">{{ $order->created_at->format('M d, Y') }}</span></span>
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-gray-500">No recent orders found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection