@extends('components.dashboard')

@section('page-title', 'Raw Materials Management')
@section('page-description', 'View and manage raw materials ')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 md:p-6 dark:bg-gray-800 dark:border dark:border-gray-700">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Raw Materials</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your raw materials </p>
        </div>
        
        @if (Auth::user()->category === 'supplier' || Auth::user()->category === 'systemadmin')
            <a href="{{ route('raw_materials.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition duration-150">
                <i class="fas fa-plus mr-2"></i> Add New Material
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-700 text-green-700 dark:text-green-100 p-4 rounded flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Materials Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center">
                            <span>Name</span>
                            <a href="#"><i class="fas fa-sort ml-1 text-gray-400 hover:text-gray-500"></i></a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Quantity
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Unit
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Unit Price
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Owner
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($materials as $material)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-box text-gray-500 dark:text-gray-300"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $material->name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $material->code ?? '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $material->type }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ number_format($material->quantity) }} <span class="font-semibold">{{ $material->measurement_unit }}</span></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $material->unit }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        UGX {{ number_format($material->unit_price) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full overflow-hidden">
                                <img class="h-full w-full" src="https://ui-avatars.com/api/?name={{ urlencode($material->user->name ?? 'U') }}&background=random" alt="">
                            </div>
                            <div class="ml-3">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $material->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($material->user->category ?? '') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-3">
                            @if(Auth::user()->id === $material->user_id || Auth::user()->category === 'systemadmin')
                                <a href="{{ route('raw_materials.edit', $material) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition duration-150"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(Auth::user()->category === 'systemadmin')
                                <form action="{{ route('raw_materials.destroy', $material) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this material?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-150"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            @else
                                <span class="text-xs text-gray-400 dark:text-gray-500 italic">No Actions</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fas fa-box-open text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400">No materials found</p>
                            @if(Auth::user()->category === 'supplier' || Auth::user()->category === 'systemadmin')
                            <a href="{{ route('raw_materials.create') }}" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                                <i class="fas fa-plus mr-2"></i> Add Your First Material
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($materials->hasPages())
    <div class="mt-6">
        {{ $materials->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any raw materials specific JavaScript here
    });
</script>
@endpush