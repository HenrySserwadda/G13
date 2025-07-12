@extends('components.dashboard')

@section('page-title', 'Staff Dashboard')
@section('page-description', 'Your workspace for managing materials, inventory, and orders.')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-md p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="opacity-90">Manage your materials, inventory, and orders efficiently.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-block bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">
                    {{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Key Metrics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Material Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Pending Material Orders</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $pendingMaterialOrders ?? 0 }}</h3>
                    </div>
                </div>
                <a href="{{ route('raw-material-orders.index') }}" class="block mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">View all</a>
            </div>
            <!-- Low Stock Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-exclamation-triangle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Low Stock Items</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $lowStockItems ?? 0 }}</h3>
                    </div>
                </div>
                <a href="{{ route('inventories.index') }}" class="block mt-3 text-sm text-green-600 hover:text-green-800 font-medium">Reorder now</a>
            </div>
            <!-- Recent Deliveries -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-truck text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Recent Deliveries</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $recentDeliveries ?? 0 }}</h3>
                    </div>
                </div>
                <a href="{{ route('raw-material-orders.index') }}" class="block mt-3 text-sm text-purple-600 hover:text-purple-800 font-medium">Track</a>
            </div>
            <!-- Tasks Due -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-amber-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-tasks text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Tasks Due</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $tasksDue ?? 0 }}</h3>
                    </div>
                </div>
                <a href="#" class="block mt-3 text-sm text-amber-600 hover:text-amber-800 font-medium">View tasks</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('raw_materials.index') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-blue-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <h3 class="font-medium">Raw Materials</h3>
            </a>
            <a href="{{ route('inventories.index') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-green-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-warehouse text-green-600"></i>
                </div>
                <h3 class="font-medium">Inventory</h3>
            </a>
            <a href="{{ route('raw-material-orders.index') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-purple-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-shopping-cart text-purple-600"></i>
                </div>
                <h3 class="font-medium">Material Orders</h3>
            </a>
            <a href="#" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-amber-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-file-alt text-amber-600"></i>
                </div>
                <h3 class="font-medium">Generate Report</h3>
            </a>
        </div>
    </div>

    <!-- Recent Activity & Tasks -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Activity</h2>
                <a href="#" class="text-blue-600 text-sm hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start">
                        <div class="bg-gray-100 p-2 rounded-full mr-3">
                            <i class="fas fa-{{ $activity->icon }} text-gray-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500">No recent activity to display</p>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Your Tasks</h2>
                <a href="#" class="text-blue-600 text-sm hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($tasks as $task)
                    <div class="flex items-start p-3 rounded-lg border border-gray-200 hover:shadow-md transition">
                        <div class="mt-1 mr-3">
                            <input type="checkbox" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" @if($task->completed) checked @endif>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $task->due_date->diffForHumans() }}</p>
                            <div class="mt-2 flex items-center text-sm">
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $task->category }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No tasks assigned.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Team Communication -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-comments text-indigo-500 mr-2"></i>
            Team Communication
        </h2>
        @livewire('chat')
    </div>
</div>
@endsection
