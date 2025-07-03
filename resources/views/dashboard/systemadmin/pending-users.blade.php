@extends('components.dashboard')
@section('title', 'Pending Users - DURABAG')
@section('content')
    <!-- Content -->
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
        <table class="w-full table-auto text-left border-collapse ">
            <thead>
                <tr class="max-w-m pt-16">
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Category</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Approve</th> 
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Reject</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="max-w-m">
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->name }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->category }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('dashboard.systemadmin.approve',$user->id) }}">
                                @csrf
                                <x-primary-button>Approve</x-primary-button>
                            </form>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('dashboard.systemadmin.reject',$user->id) }}">
                                @csrf
                                <x-primary-button>Reject</x-primary-button>
                            </form>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
        
            
