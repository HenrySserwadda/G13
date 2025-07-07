@extends('components.dashboard')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Add Inventory Entry</h2>
</div>

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong class="font-bold">Whoops!</strong> There were some problems with your input.
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('inventories.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded shadow border">
    @csrf

    <!-- Raw Material Selection -->
    <div>
        <label for="raw_material_id" class="block text-sm font-medium text-gray-700">Raw Material</label>
        <select id="raw_material_id" name="raw_material_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select Material</option>
            @foreach ($rawMaterials as $material)
                <option value="{{ $material->id }}" {{ old('raw_material_id') == $material->id ? 'selected' : '' }}>
                    {{ $material->name }} ({{ $material->type }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Quantities -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="on_hand" class="block text-sm font-medium text-gray-700">Quantity On Hand</label>
            <input type="number" name="on_hand" id="on_hand" min="0" value="{{ old('on_hand') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <span id="available-quantity" class="text-xs text-gray-500"></span>
        </div>
        <div>
            <label for="on_order" class="block text-sm font-medium text-gray-700">Quantity On Order</label>
            <input type="number" name="on_order" id="on_order" min="0" value="{{ old('on_order') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Status Dropdowns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="stock_status" class="block text-sm font-medium text-gray-700">Stock Status</label>
            <select name="stock_status" id="stock_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="low" {{ old('stock_status') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="out" {{ old('stock_status') == 'out' ? 'selected' : '' }}>Out</option>
            </select>
        </div>

        <div>
            <label for="delivery_status" class="block text-sm font-medium text-gray-700">Delivery Status</label>
            <select name="delivery_status" id="delivery_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="received" {{ old('delivery_status') == 'received' ? 'selected' : '' }}>Received</option>
                <option value="in_transit" {{ old('delivery_status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                <option value="need_to_order" {{ old('delivery_status') == 'need_to_order' ? 'selected' : '' }}>Need to Order</option>
                <option value="in_progress" {{ old('delivery_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            </select>
        </div>
    </div>

    <!-- Dates -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="delivered_on" class="block text-sm font-medium text-gray-700">Delivered On</label>
            <input type="date" name="delivered_on" id="delivered_on" value="{{ old('delivered_on') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="expected_delivery" class="block text-sm font-medium text-gray-700">Expected Delivery</label>
            <input type="date" name="expected_delivery" id="expected_delivery" value="{{ old('expected_delivery') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
            <i class="fas fa-save mr-2"></i>Save Entry
        </button>
        <a href="{{ route('inventories.index') }}" class="ml-4 text-sm text-gray-600 hover:underline">Cancel</a>
    </div>
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('scripts')
<script>
    const rawMaterialQuantities = @json($rawMaterialQuantities ?? []);
    document.addEventListener('DOMContentLoaded', function() {
        const materialSelect = document.getElementById('raw_material_id');
        const availableQuantitySpan = document.getElementById('available-quantity');
        function updateAvailableQuantity() {
            const selectedId = materialSelect.value;
            if (rawMaterialQuantities[selectedId] !== undefined) {
                availableQuantitySpan.textContent = `Available: ${rawMaterialQuantities[selectedId]}`;
            } else {
                availableQuantitySpan.textContent = '';
            }
        }
        materialSelect.addEventListener('change', updateAvailableQuantity);
        updateAvailableQuantity();
    });
</script>
@endpush