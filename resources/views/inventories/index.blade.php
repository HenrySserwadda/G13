@extends('components.dashboard')

@section('page-title', 'Inventory Management')
@section('page-description', 'View and manage your inventory records')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 md:p-6 dark:bg-gray-800 dark:border dark:border-gray-700">
    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-700 text-green-700 dark:text-green-100 p-4 rounded">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(Auth::user()->category === 'supplier' || Auth::user()->category === 'systemadmin')
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Inventory Records</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage your raw material inventory</p>
            </div>
            <a href="{{ route('inventories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition duration-150">
                <i class="fas fa-plus mr-2"></i> Add Inventory
            </a>
        </div>
    @else
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Inventory Records</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">View inventory status</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center">
                            <span>Material</span>
                            <a href="#"><i class="fas fa-sort ml-1 text-gray-400 hover:text-gray-500"></i></a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        On Hand
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        On Order
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Stock Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Delivered On
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Expected
                    </th>
                    @if(Auth::user()->category === 'supplier' || Auth::user()->category === 'systemadmin')
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Actions
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($inventories as $inventory)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-cube text-gray-500 dark:text-gray-300"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $inventory->rawMaterial->name ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $inventory->rawMaterial->code ?? '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $inventory->on_hand < ($inventory->rawMaterial->minimum_stock_level ?? 0) ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                            {{ $inventory->on_hand }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $inventory->on_order }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($inventory->stock_status === 'in_stock') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($inventory->stock_status === 'low') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($inventory->stock_status === 'out') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                            {{ ucfirst(str_replace('_', ' ', $inventory->stock_status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'in_transit' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            ];
                            $statusClass = $statusClasses[$inventory->delivery_status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $inventory->delivery_status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $inventory->delivered_on ? $inventory->delivered_on->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $inventory->expected_delivery ? \Carbon\Carbon::parse($inventory->expected_delivery)->format('M d, Y') : 'N/A' }}
                    </td>
                    @if(Auth::user()->category === 'supplier' || Auth::user()->category === 'systemadmin')
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('inventories.edit', $inventory) }}" 
                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition duration-150"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('inventories.destroy', $inventory) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this inventory record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-150"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->category === 'supplier' || Auth::user()->category === 'systemadmin' ? 7 : 6 }}" class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fas fa-warehouse text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400">No inventory records found</p>
                            @if(Auth::user()->category === 'supplier' || Auth::user()->category === 'systemadmin')
                            <a href="{{ route('inventories.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                                <i class="fas fa-plus mr-2"></i> Add Your First Inventory
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($inventories->hasPages())
    <div class="mt-6">
        {{ $inventories->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any inventory-specific JavaScript here
    });
</script>
@endpush