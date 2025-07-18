@extends('components.dashboard')
@section('title', 'Pending Users - DURABAG')
@section('content')
<x-usermgt />
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>
@endif
    <table class="w-full table-auto text-left border-collapse ">
        <thead>
            <tr class="max-w-m pt-16">
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Email</th>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Approve/Reject</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="max-w-m">
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->name }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('dashboard.systemadmin.handleAdminAction', $user->id) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="action" class="border rounded px-2 py-1 text-sm">
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                            </select>
                            <button type="submit" class="text-green-600 text-xl hover:text-green-800" title="Confirm Action">
                                ✔️
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
        
            
