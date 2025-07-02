<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DURABAG Dashboard - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <header>
        <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start rtl:justify-end">
                        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                            </svg>
                        </button>
                        <a href="{{ url('/') }}" class="flex ms-2 md:me-24">
                            <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 me-3" alt="FlowBite Logo" />
                            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">DURABAG</span>
                        </a>
                        <!-- Cart Link -->
                        @php $user = Auth::user(); @endphp
                        @if($user && in_array($user->category, ['wholesaler', 'retailer']))
                        <a href="{{ route('cart.show') }}" class="relative ml-4 inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200 dark:bg-gray-800 dark:text-blue-300 dark:hover:bg-gray-700">
                            <i class="fas fa-shopping-cart mr-1"></i> Cart
                            @php $cart = session('cart', []); $cartCount = array_sum(array_column($cart, 'quantity')); @endphp
                            @if($cartCount > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $cartCount }}</span>
                            @endif
                        </a>
                        @endif
                    </div>
                    <div class="flex items-center">
                        <div class="flex items-center ms-3">
                            <div>
                                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=random" alt="user photo">
                                </button>
                            </div>
                            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow-sm dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                                <div class="px-4 py-3" role="none">
                                    <p class="text-sm text-gray-900 dark:text-white" role="none">
                                        {{ Auth::user()->name ?? 'User' }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                        {{ ucfirst(Auth::user()->category ?? 'Role') }}
                                    </p>
                                </div>
                                <ul class="py-1" role="none">
                                    <li>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profile</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Logout</a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>  
    
    <div class="flex min-h-screen pt-16">  
        <!-- Sidebar -->
        <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
            <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
                <ul class="space-y-2 font-medium">
                    <!-- Dashboard -->
                    <li>
                        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-tachometer-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    
                    @auth
                        <!-- Orders -->
                        @if(in_array(Auth::user()->category, ['supplier', 'systemadmin', 'staff']))
                        <li>
                            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-shopping-cart w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Orders</span>
                                @php
                                    $orderCount = \App\Models\Order::where('user_id', Auth::id())->count();
                                @endphp
                                <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">{{ $orderCount }}</span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->category === 'wholesaler' || Auth::user()->category === 'retailer')
                         <li>
                            <a href="{{ route('user-orders.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-shopping-cart w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Orders</span>
                               <!-- <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">3</span>-->
                            </a>
                        </li>
                        @endif
                                            <li>
                        <a href="{{ route('chat') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Chat</span>
                        <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
    <!--dont forget to edit the 3 part here so that it cann correspond to the actual messages that the user has.It should be a variable-->
                        </a>
                    </li>
                

                        <!-- Products -->
                        @if(in_array(Auth::user()->category, ['systemadmin', 'wholesaler', 'staff', 'retailer']))
                        <li>
                            <a href="{{ route('products.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-box-open w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Products</span>
                            </a>
                        </li>
                        @endif

                        <!-- Raw Materials -->
                        @if(in_array(Auth::user()->category, ['systemadmin', 'staff', 'supplier']))
                        <li>
                            <a href="{{ route('raw_materials.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-cubes w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Raw Materials</span>
                            </a>
                        </li>
                        @endif

                        <!-- Inventory -->
                        @if(Auth::user()->category === 'supplier')
                        <li>
                            <a href="{{ route('inventories.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-warehouse w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Inventory</span>
                            </a>
                        </li>
                        
                        @elseif(in_array(Auth::user()->category, ['systemadmin', 'staff', 'wholesaler','retailer']))
                        <li>
                            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-warehouse w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Inventory</span>
                            </a>
                        </li>
                        @endif

                        <!-- Reports -->
                        @if(in_array(Auth::user()->category, ['systemadmin', 'staff']))
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="reports-dropdown" data-collapse-toggle="reports-dropdown">
                                <i class="fas fa-chart-bar w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Reports</span>
                                <i class="fas fa-chevron-down w-3 h-3"></i>
                            </button>
                            <ul id="reports-dropdown" class="hidden py-2 space-y-2">
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Sales</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Inventory</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Financial</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Analytics -->
                        @if(in_array(Auth::user()->category, ['systemadmin', 'staff']))
                        <li>
                            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-chart-line w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Analytics</span>
                            </a>
                        </li>
                        @endif

                        <!-- Users (Admin Only) -->
                        @if(Auth::user()->category === 'systemadmin')
                        <li>
                            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-users w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                            </a>
                        </li>
                        @endif
                    @endauth

                   

                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <i class="fas fa-sign-out-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Logout</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6 ml-0 sm:ml-64">
            <!-- Breadcrumb Navigation -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <i class="fas fa-home mr-2"></i>
                            Home
                        </a>
                    </li>
                    @yield('breadcrumbs')
                </ol>
            </nav>
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">@yield('page-description', 'Overview of your dashboard')</p>
                </div>
                <div class="mt-4 md:mt-0">
                    @yield('page-actions')
                </div>
            </div>
            
            <!-- Stats Cards -->
            @hasSection('stats')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @yield('stats')
            </div>
            @endif
            
            <!-- Main Content Container -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6 border border-gray-200">
                <!-- Content Wrapper with optional header -->
                @hasSection('content-header')
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-200">
                    @yield('content-header')
                </div>
                @endif
                
                <!-- Actual Content -->
                <div class="@hasSection('content-class') @yield('content-class') @else w-full @endif">
                    @yield('content')
                </div>
                
                <!-- Optional footer -->
                @hasSection('content-footer')
                <div class="mt-6 pt-4 border-t border-gray-200">
                    @yield('content-footer')
                </div>
                @endif
            </div>
            
            <!-- Optional Secondary Content Area -->
            @hasSection('secondary-content')
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6">
                @yield('secondary-content')
            </div>
            @endif
        </main>
    </div>  

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        // Initialize sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('[data-drawer-toggle="logo-sidebar"]');
            const sidebar = document.getElementById('logo-sidebar');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });
            
            // Initialize dropdowns
            const dropdownButtons = document.querySelectorAll('[data-collapse-toggle]');
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-collapse-toggle');
                    const target = document.getElementById(targetId);
                    target.classList.toggle('hidden');
                    
                    // Rotate chevron icon
                    const icon = this.querySelector('i.fa-chevron-down');
                    if (icon) {
                        icon.classList.toggle('rotate-180');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>