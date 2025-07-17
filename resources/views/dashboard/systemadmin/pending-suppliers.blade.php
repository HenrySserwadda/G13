@extends('components.dashboard')
@section('title', 'Pending Suppliers - DURABAG')
@section('content')
<x-usermgt />
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
        
            
