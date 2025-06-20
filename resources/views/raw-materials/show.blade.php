@extends('dashboard') {{-- or your main layout file --}}

@section('content')
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $material->name }}</h1>
                <p class="text-gray-600">{{ $material->code }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('raw-materials.edit', $material) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Edit Material
                </a>
                <a href="{{ route('raw-materials.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Material Details -->
            <div class="bg-white rounded-lg shadow-sm p-6 col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Category</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $material->category }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $material->description ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Supplier</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $material->supplier->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Location</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $material->location ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Current Stock</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $material->current_stock }} {{ $material->unit_of_measure }}
                                    @if($material->isLowStock())
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Low Stock</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Minimum Stock Level</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $material->minimum_stock }} {{ $material->unit_of_measure }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Unit Cost</label>
                                <p class="mt-1 text-sm text-gray-900">USX {{ number_format($material->unit_cost, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Total Stock Value</label>
                                <p class="mt-1 text-sm text-gray-900">USX {{ number_format($material->stockValue(), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Material Image -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Material Image</h3>
                @if($material->image_path)
                <div class="h-64 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('storage/' . $material->image_path) }}" alt="{{ $material->name }}" class="h-full w-full object-contain">
                </div>
                @else
                <div class="h-64 bg-gray-100 rounded-md flex items-center justify-center">
                    <span class="text-gray-400">No image available</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Stock Adjustment Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Adjust Stock Level</h3>
            <form action="{{ route('raw-materials.stock-adjustment', $material) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="adjustment_type" class="block text-sm font-medium text-gray-700">Adjustment Type</label>
                        <select name="adjustment_type" id="adjustment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            <option value="addition">Addition</option>
                            <option value="deduction">Deduction</option>
                        </select>
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" step="0.01" name="quantity" id="quantity" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                        <input type="text" name="reason" id="reason" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md">
                            Update Stock
                        </button>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="2" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"></textarea>
                </div>
            </form>
        </div>

        <!-- Stock Movement History -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Movement History</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($stockHistory as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($movement->type === 'addition')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Addition</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Deduction</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->quantity }} {{ $material->unit_of_measure }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->new_stock }} {{ $material->unit_of_measure }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $movement->reason }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->user->name ?? 'System' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No stock movements recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $stockHistory->links() }}
            </div>
        </div>
    </div>
</div>
@endsection