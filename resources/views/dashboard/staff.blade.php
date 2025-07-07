@extends('components.dashboard')

@section('page-title', 'Staff Dashboard')
@section('page-description', 'Overview of your staff dashboard')

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
        <h1 class="text-2xl font-bold mb-4">Staff Dashboard</h1>
        <p class="mb-6">Welcome to the staff dashboard. Here you can manage your tasks and view important information.</p>

        <div class="mb-8 space-y-4">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('raw_materials.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition duration-150">
                    <i class="fas fa-box mr-2"></i> View Raw Materials
                </a>
                <a href="{{ route('inventories.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition duration-150">
                    <i class="fas fa-warehouse mr-2"></i> View Inventory
                </a>
                <a href="{{ route('raw-material-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow transition duration-150">
                    <i class="fas fa-shopping-cart mr-2"></i> Raw Material Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Example Card -->
            <div class="bg-gray-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-2">Pending Tasks</h2>
                <p>You have 3 pending tasks to complete.</p>
            </div>
            <!-- Another Example Card -->
            <div class="bg-gray-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-2">Recent Activities</h2>
                <p>Check your recent activities and updates.</p>
            </div>
        </div>
    </div>
@endsection
   