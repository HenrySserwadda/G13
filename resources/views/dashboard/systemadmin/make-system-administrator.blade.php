
@extends('components.dashboard')
@section('title', 'Make System Administrator - DURABAG')
@section('content')
    <!-- Page Content -->
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
