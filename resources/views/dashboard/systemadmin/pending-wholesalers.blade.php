@extends('components.dashboard')
@section('title', 'Pending Wholesalers - DURABAG')
@section('content')
<x-usermgt />
    <table class="w-full table-auto text-left border-collapse ">
        <thead>
            <tr class="max-w-m pt-16">
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Email</th>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Approve</th> 
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Reject</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="max-w-m">
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->name }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('dashboard.systemadmin.approve-wholesalers',$user->id) }}">
                            @csrf
                            <x-primary-button>Approve</x-primary-button>
                        </form>
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('dashboard.systemadmin.rejectWholesalers',$user->id) }}">
                            @csrf
                            <x-primary-button>Reject</x-primary-button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
        
            
