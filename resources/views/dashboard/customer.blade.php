@extends('components.dashboard')

@section('page-title', 'Customer Dashboard')
@section('page-description', 'Overview of your customer dashboard')


@section('content')    
<div class="filter mb-3 flex justify-end">
    <form action="{{ route('application') }}" method="POST" class="flex items-center gap-4 bg-white dark:bg-gray-300 p-4 rounded-lg shadow-sm w-full max-w-xs">
        @csrf
        <div class="relative flex-grow">
            <select name="categories" id="application" class="block w-full px-4 py-2 pr-8 leading-tight bg-white dark:bg-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none text-gray-800">
                <option value="retailer" {{ (session('selected_category') == 'retailer' || request('categories') == 'retailer') ? 'selected' : '' }}>Retailer</option>
                <option value="supplier" {{ (session('selected_category') == 'supplier' || request('categories') == 'supplier') ? 'selected' : '' }}>Supplier</option>
                <option value="wholesaler" {{ (session('selected_category') == 'wholesaler' || request('categories') == 'wholesaler') ? 'selected' : '' }}>Wholesaler</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
            Apply
        </button>
    </form>
</div>
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>
@endif
@endsection