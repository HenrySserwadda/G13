@extends('components.dashboard')

@section('page-title', 'Staff Dashboard')
@section('page-description', 'Your workspace for managing materials, inventory, and orders.')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100/20">
    <div class="container mx-auto px-4 py-8">
        <!-- Welcome Header Section -->
        <div class="mb-10">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/20 rounded-full transform translate-x-16 -translate-y-16"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->name }}!</h1>
                            <p class="text-gray-600">Here you can manage materials, inventory, and orders.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full">
                                <i class="fas fa-user-cog text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quick Access Tiles -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
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
                    <p class="text-gray-600 text-sm mb-4">View and manage all material orders</p>
                    <div class="text-green-600 text-sm font-medium flex items-center">
                        <span>View Orders</span>
                        <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1 duration-200"></i>
                    </div>
                </div>
            </a>
            <!-- Inventory Tile -->
            <a href="{{ route('inventories.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-blue-300 overflow-hidden">
                <div class="absolute inset-0 bg-blue-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 text-blue-600">
                            <i class="fas fa-warehouse text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Inventory</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Manage and monitor inventory</p>
                    <div class="text-blue-600 text-sm font-medium flex items-center">
                        <span>View Inventory</span>
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
            <!-- Raw Materials Tile -->
            <a href="{{ route('raw_materials.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-yellow-300 overflow-hidden">
                <div class="absolute inset-0 bg-yellow-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                <div class="relative z-10 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4 text-yellow-600">
                            <i class="fas fa-cubes text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Raw Materials</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">View and manage raw materials</p>
                    <div class="text-yellow-600 text-sm font-medium flex items-center">
                        <span>View Materials</span>
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
            <a href="{{ route('inventories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition duration-150">
                <i class="fas fa-warehouse mr-2"></i> Add Inventory
            </a>
            <a href="{{ route('raw-material-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition duration-150">
                <i class="fas fa-clipboard-list mr-2"></i> Place Order
            </a>
        </div>
        <!-- Stats Section -->
      <!--  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-lg shadow p-6 flex items-center border border-gray-200">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $pendingOrders }}</div>
                    <div class="text-gray-500 text-sm">Pending Orders</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 flex items-center border border-gray-200">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $lowStockItems }}</div>
                    <div class="text-gray-500 text-sm">Low Stock Items</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 flex items-center border border-gray-200">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-truck text-green-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $recentDeliveries }}</div>
                    <div class="text-gray-500 text-sm">Recent Deliveries</div>
                </div>
            </div>
        </div> -->
        <!-- Tasks Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-10 border border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Tasks Due ({{ $tasksDue }})</h2>
            <div class="space-y-3">
                @foreach($tasks as $task)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-{{ $task->completed ? 'green' : 'yellow' }}-500 rounded-full mr-3"></div>
                            <div>
                                <div class="font-medium">{{ $task->title }}</div>
                                <div class="text-sm text-gray-500">{{ $task->category }} • Due {{ $task->due_date->diffForHumans() }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs bg-{{ $task->completed ? 'green' : 'yellow' }}-100 text-{{ $task->completed ? 'green' : 'yellow' }}-800 rounded">
                            {{ $task->completed ? 'Completed' : 'Pending' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Recent Activities -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activities</h2>
            <div class="space-y-3">
                @foreach($recentActivities as $activity)
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-{{ $activity->icon }} text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium">{{ $activity->description }}</div>
                            <div class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
