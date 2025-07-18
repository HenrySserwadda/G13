<!--<div>
    - It is quality rather than quantity that matters. - Lucius Annaeus Seneca --
</div>-->
<div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
    <div>
        <!-- all users -->
        <a href="{{ route('dashboard.systemadmin.all-users') }}" class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
            @if(Request::routeIs('dashboard.systemadmin.all-users'))text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
            @else 
            text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
            @endif 
            whitespace-nowrap cursor-pointer">
                All users
        </a>
    </div>
    <div>
        <!-- pending retailers-->
        <a href="{{ route('dashboard.systemadmin.pending-retailers') }}" class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
            @if(Request::routeIs('dashboard.systemadmin.pending-retailers')) text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
            @else
                text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
            @endif
                whitespace-nowrap cursor-pointer">
            Pending Retailers
        </a>
    </div>
    <div>
        <!-- pending suppliers-->
        <a href="{{ route('dashboard.systemadmin.pending-suppliers') }}" class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
            @if(Request::routeIs('dashboard.systemadmin.pending-suppliers')) text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
            @else 
                text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
            @endif
                whitespace-nowrap cursor-pointer">
            Pending Suppliers
        </a>
    </div>
    <div>
        <!-- pending wholesalers-->
        <a href="{{ route('dashboard.systemadmin.pending-wholesalers') }}" class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
            @if(Request::routeIs('dashboard.systemadmin.pending-wholesalers')) text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
            @else 
                text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
            @endif
                whitespace-nowrap cursor-pointer">
            Pending Wholesalers
        </a>
    </div>
    <div>
        <!-- to make-system-admin -->
        <a href="{{ route('dashboard.systemadmin.make-system-administrator') }}" class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
            @if(Request::routeIs('dashboard.systemadmin.make-system-administrator')) text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
            @else
                text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
            @endif
                whitespace-nowrap cursor-pointer">
            Make System Admin
        </a>
    </div>
    <div>
        <!--to make a staff member-->
        <a href="{{ route('dashboard.systemadmin.make-staff-member') }}" class="py-2 px-4 text-sm font-medium text-center transition-colors duration-200
            @if(Request::routeIs('dashboard.systemadmin.make-staff-member')) text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500
            @else
                text-gray-500 hover:text-gray-700 hover:border-gray-300dark:text-gray-700 dark:hover:text-gray-200 dark:hover:border-gray-500
            @endif
                whitespace-nowrap cursor-pointer">
            Make Staff Member
        </a>
    </div>
</div>