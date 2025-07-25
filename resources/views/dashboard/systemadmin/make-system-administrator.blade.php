@extends('components.dashboard')
@section('title', 'Make System Administrator - DURABAG')
@section('content')
    <!-- Page Content -->
    <x-usermgt />
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" id="myAlert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" data-dismiss="alert">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dismissButton = document.querySelector('[data-dismiss="alert"]');
                if (dismissButton) {
                    dismissButton.addEventListener('click', function() {
                        this.closest('[role="alert"]').style.display = 'none';
                    });
                }
            });
        </script>
    @endif
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
                    <form method="POST" action="{{ route('dashboard.systemadmin.make-systemadmin',$user->id) }}" onsubmit="return confirm('Are you sure you want to make this user a systemadmin?')">
                        <x-primary-button>Make system admin</x-primary-button>
                        @csrf
                    </form>
                </td>
            </tr>  
            @endforeach           
    </table>
@endsection
