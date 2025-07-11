{{-- filepath: resources/views/dashboard/systemadmin/all-users.blade.php --}}
@extends('components.dashboard')
@section('title', 'All Users - DURABAG')


@section('content')
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
        <!-- all users -->
        <a href="{{ route('dashboard.systemadmin.all-users') }}"
           class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
                  @if(Request::routeIs('dashboard.systemadmin.all-users'))
                      text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
                  @else
                      text-gray-500 hover:text-gray-700 hover:border-gray-300
                      dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
                  @endif
                  whitespace-nowrap cursor-pointer">
            All users
        </a>

        <!-- pending -->
        <a href="{{ route('dashboard.systemadmin.pending-users') }}"
           class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
                  @if(Request::routeIs('dashboard.systemadmin.pending-users'))
                      text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
                  @else
                      text-gray-500 hover:text-gray-700 hover:border-gray-300
                      dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
                  @endif
                  whitespace-nowrap cursor-pointer">
            Pending users
        </a>

        <!-- to make-system-admin -->
        <a href="{{ route('dashboard.systemadmin.make-system-administrator') }}"
           class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
                  @if(Request::routeIs('dashboard.systemadmin.make-system-administrator'))
                      text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
                  @else
                      text-gray-500 hover:text-gray-700 hover:border-gray-300
                      dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
                  @endif
                  whitespace-nowrap cursor-pointer">
            Make System Admin
        </a>
    </div>
    {{--<div class="filter">
        <form action="{{ route('filter') }}" method="GET">
            <select name="categories" id="filter">
                <option value="all">all users</option>
                <option value="staff">Staff</option>
                <option value="customer">Customer</option>
                <option value="retailer">Retailer</option>
                <option value="supplier">Supplier</option>
                <option value="systemadmin">System administrator</option>
                <option value="wholesaler">Wholesaler</option>
            </select>
            <x-primary-button class="bg-green-200">Filter</x-primary-button>
        </form>
    </div>--}}
    <div class="filter mb-3 flex justify-end">
    <form action="{{ route('filter') }}" method="GET" class="flex items-center gap-4 bg-white dark:bg-gray-300 p-4 rounded-lg shadow-md w-full max-w-md">
        <div class="relative flex-grow">
            <select name="categories" id="filter" class="block w-full px-4 py-2 pr-8 leading-tight bg-white dark:bg-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none text-gray-800">
                <option value="all" {{ request('categories') == 'all' ? 'selected' : '' }}>All Users</option>
                <option value="staff" {{ request('categories') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="customer" {{ request('categories') == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="retailer" {{ request('categories') == 'retailer' ? 'selected' : '' }}>Retailer</option>
                <option value="supplier" {{ request('categories') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                <option value="systemadmin" {{ request('categories') == 'systemadmin' ? 'selected' : '' }}>System Administrator</option>
                <option value="wholesaler" {{ request('categories') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
            Filter
        </button>
    </form>
</div>
    <table class="w-full table-auto text-left border-gray-300 border-collapse ">
        <tr>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">User ID</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Category</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Email</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Delete</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->name }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->user_id }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->category }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('delete', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <x-primary-button>Delete</x-primary-button>
                    </form>
                </td>
            </tr>
        @endforeach           
    </table>
@endsection