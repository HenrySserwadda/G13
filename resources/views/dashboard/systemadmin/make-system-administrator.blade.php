@extends('components.dashboard')
@section('title', 'Make System Administrator - DURABAG')
@section('content')
    <!-- Page Content -->
    <x-usermgt />
    <table class="w-full table-auto text-left border-gray-300 border-collapse ">
        <tr>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Category</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Email</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Make system admin</th>
        </tr>
        @foreach($users as $user)         
            <tr>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->name }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->category }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('dashboard.systemadmin.make-systemadmin',$user->id) }}">
                        <x-primary-button>Make system admin</x-primary-button>
                        @csrf
                    </form>
                </td>
            </tr>  
            @endforeach           
    </table>
@endsection
