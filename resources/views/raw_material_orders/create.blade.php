@extends('components.dashboard')
@section('page-title', 'Order Raw Materials')
@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Order Raw Materials</h2>
            <a href="{{ route('raw-material-orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Orders
            </a>
        </div>

        <form method="POST" action="{{ route('raw-material-orders.store') }}" class="space-y-6">
            @csrf
            
            <div class="mb-6">
                <label for="supplier_select" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <select name="supplier_user_id" id="supplier_select" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">
                            {{ $supplier->name }} ({{ $supplier->user_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-4" id="items">
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Raw Material</label>
                        <select name="items[0][raw_material_id]" id="raw_material_select_0" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Raw Material</option>
                        </select>
                    </div>
                    <div class="w-32">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" name="items[0][quantity]" id="quantity_input_0" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="1" placeholder="Qty" required>
                    </div>
                    <div class="w-32">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                        <input type="number" step="0.01" name="items[0][price]" id="price_input_0" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" placeholder="Price" required>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <button type="button" onclick="addItem()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Another Item
                </button>

                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <span class="text-sm font-medium text-gray-700">Total Amount: </span>
                        <span id="order_total" class="text-lg font-bold text-blue-600">0.00</span>
                    </div>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Place Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = 1;
let supplierSelect = document.getElementById('supplier_select');

function loadRawMaterials(supplierId, selectId, quantityInputId) {
    let select = document.getElementById(selectId);
    let quantityInput = document.getElementById(quantityInputId);
    select.innerHTML = '<option value="">Loading...</option>';
    fetch(`/supplier/${supplierId}/raw-materials`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Select Raw Material</option>';
            data.forEach(material => {
                options += `<option value="${material.id}" data-quantity="${material.quantity}" data-price="${material.unit_price}">${material.name} (Available: ${material.quantity}, Unit: ${material.unit_price})</option>`;
            });
            select.innerHTML = options;
            // Reset quantity and price input
            if (quantityInput) quantityInput.value = '';
            let priceInput = select.parentElement.parentElement.querySelector('input[name^="items"][name$="[price]"]');
            if (priceInput) priceInput.value = '';
        });
    // Add event listener to update max quantity and price
    select.onchange = function() {
        let selected = select.options[select.selectedIndex];
        let available = selected.getAttribute('data-quantity');
        let unitPrice = selected.getAttribute('data-price');
        if (quantityInput && available) {
            quantityInput.max = available;
            quantityInput.placeholder = `Max: ${available}`;
        }
        let priceInput = select.parentElement.parentElement.querySelector('input[name^="items"][name$="[price]"]');
        if (priceInput && unitPrice) {
            priceInput.value = unitPrice;
            priceInput.placeholder = `Unit: ${unitPrice}`;
        }
        updateOrderTotal();
    };
}

supplierSelect.addEventListener('change', function() {
    document.querySelectorAll('select[name^="items"][name$="[raw_material_id]"]').forEach((select, idx) => {
        let quantityInput = select.parentElement.parentElement.querySelector('input[name^="items"][name$="[quantity]"]');
        loadRawMaterials(this.value, select.id, quantityInput.id);
    });
});

function addItem() {
    const itemsDiv = document.getElementById('items');
    const html = `
    <div class="flex items-end gap-4">
        <div class="flex-1">
            <select name="items[${itemIndex}][raw_material_id]" id="raw_material_select_${itemIndex}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Raw Material</option>
            </select>
        </div>
        <div class="w-32">
            <input type="number" name="items[${itemIndex}][quantity]" id="quantity_input_${itemIndex}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="1" placeholder="Qty" required>
        </div>
        <div class="w-32">
            <input type="number" step="0.01" name="items[${itemIndex}][price]" id="price_input_${itemIndex}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" placeholder="Price" required>
        </div>
    </div>`;
    itemsDiv.insertAdjacentHTML('beforeend', html);
    let supplierId = supplierSelect.value;
    if (supplierId) {
        loadRawMaterials(supplierId, `raw_material_select_${itemIndex}`, `quantity_input_${itemIndex}`);
    }
    itemIndex++;
}

// Calculate and display order total
function updateOrderTotal() {
    let total = 0;
    document.querySelectorAll('#items > div').forEach(row => {
        let qty = row.querySelector('input[name^="items"][name$="[quantity]"]');
        let price = row.querySelector('input[name^="items"][name$="[price]"]');
        if (qty && price && qty.value && price.value) {
            total += parseFloat(qty.value) * parseFloat(price.value);
        }
    });
    document.getElementById('order_total').textContent = total.toFixed(2);
}

// Initial setup for the first row
document.addEventListener('DOMContentLoaded', function() {
    let quantityInput = document.getElementById('quantity_input_0');
    loadRawMaterials(supplierSelect.value, 'raw_material_select_0', 'quantity_input_0');
    // Add event listeners for quantity and price
    document.getElementById('items').addEventListener('input', function(e) {
        if (e.target.matches('input[name^="items"][name$="[quantity]"]')) {
            updateOrderTotal();
        }
    });
});
</script>
@endsection