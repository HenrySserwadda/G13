{{-- filepath: resources/views/dashboard/systemadmin/all-users.blade.php --}}
@extends('components.dashboard')
@section('title', 'All Users - DURABAG')
@section('content')
    <x-usermgt />
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
                    <form method="POST" action="{{ route('delete', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <x-primary-button>Delete</x-primary-button>
                    </form>
                </td>
            </tr>
        @endforeach           
    </table>
@endsection