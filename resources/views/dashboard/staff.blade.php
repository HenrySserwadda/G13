@extends('components.dashboard')

@section('page-title', 'Staff Dashboard')
@section('page-description', 'Your workspace for managing materials, inventory, and orders.')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600">Here you can manage materials, inventory, and orders.</p>
    </div>
    
    <!-- Staff Dashboard Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $pendingMaterialOrders }}</div>
                <div class="text-gray-500 text-sm">Pending Orders</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $lowStockItems }}</div>
                <div class="text-gray-500 text-sm">Low Stock Items</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <i class="fas fa-truck text-green-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $recentDeliveries }}</div>
                <div class="text-gray-500 text-sm">Recent Deliveries</div>
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
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
    <div class="bg-white rounded-lg shadow p-6">
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
@endsection
