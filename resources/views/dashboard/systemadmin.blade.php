@extends('components.dashboard')

@section('page-title', 'System Admin Dashboard')
@section('page-description', 'Overview of your system admin dashboard')

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
            <div class="mt-4 md:mt-0">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Quick Action
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Key Metrics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Users Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Users</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $userCount }}</h3>
                        <p class="text-green-600 text-xs mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 12% from last month
                        </p>
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
                        <p class="text-green-600 text-xs mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 5 new this week
                        </p>
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
                        <p class="text-gray-500 text-sm">Recent Orders</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $orderCount }}</h3>
                        <p class="text-red-600 text-xs mt-1 flex items-center">
                            <i class="fas fa-arrow-down mr-1"></i> 3% from yesterday
                        </p>
                    </div>
                </div>
            </div>

            <!-- Raw Materials Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-cubes text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Raw Materials</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $rawMaterialCount }}</h3>
                        <p class="text-gray-500 text-xs mt-1">Last updated: Today</p>
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
                <h3 class="font-medium">Add User</h3>
            </a>
            <a href="{{ route('raw_materials.index') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-green-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-cube text-green-600"></i>
                </div>
                <h3 class="font-medium">Materials</h3>
            </a>
            <a href="{{ route('inventories.index') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-purple-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-warehouse text-purple-600"></i>
                </div>
                <h3 class="font-medium">Inventory</h3>
            </a>
            <a href="{{ route('reports.sales') }}" class="bg-white hover:bg-gray-50 border border-gray-200 rounded-lg p-4 text-center transition transform hover:-translate-y-1">
                <div class="bg-yellow-100 p-3 rounded-full inline-block mb-2">
                    <i class="fas fa-chart-pie text-yellow-600"></i>
                </div>
                <h3 class="font-medium">Reports</h3>
            </a>
        </div>
    </div>

    <!-- Recent Activity & System Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Activity</h2>
                <a href="#" class="text-blue-600 text-sm">View All</a>
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
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
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

        <!-- System Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">System Status</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <i class="fas fa-server text-green-600"></i>
                        </div>
                        <span class="font-medium">Database</span>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Operational</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <i class="fas fa-cloud text-green-600"></i>
                        </div>
                        <span class="font-medium">API Services</span>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Operational</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-bell text-blue-600"></i>
                        </div>
                        <span class="font-medium">Notifications</span>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Enabled</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                            <i class="fas fa-shield-alt text-yellow-600"></i>
                        </div>
                        <span class="font-medium">Security</span>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Monitoring</span>
                </div>
            </div>
            
            <!-- Storage Usage -->
            <div class="mt-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-medium">Storage Usage</span>
                    <span class="text-sm text-gray-500">65% of 50GB used</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 65%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Recent Users</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentUsers as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($user->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Showing 1 to {{ min(5, $recentUsers->count()) }} of {{ $userCount }} users
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 text-sm">Previous</button>
                <button class="px-3 py-1 rounded-md bg-blue-600 text-white text-sm">Next</button>
            </div>
        </div>
    </div>
@endsection