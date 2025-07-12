@extends('components.dashboard')
@section('title', 'Make Staff - DURABAG')
@section('content')
    <!-- Page Content -->
    <x-usermgt />
    <table class="w-full table-auto text-left border-gray-300 border-collapse ">
        <tr>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Category</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Email</th>
            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Make staff member</th>
        </tr>
        @foreach($users as $user)         
            <tr>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->name }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->category }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('dashboard.systemadmin.make-staff',$user->id) }}">
                        <x-primary-button>Make staff member</x-primary-button>
                        @csrf
                    </form>
                </td>
            </tr>  
            @endforeach           
    </table>
@endsection
