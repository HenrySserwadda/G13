<x-dashboardappearance>
    <x-slot name="rawmaterials">
        <li>
            <a href="{{ route('raw_materials.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Raw Materials</span>
            </a>
        </li>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Add New Raw Material</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('raw_materials.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Material Name</label>
                <input type="text" name="name" id="name" class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white" required>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <input type="text" name="type" id="type" class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white" required>
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white" required>
            </div>

            <div>
                <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price (UGX)</label>
                <input type="number" name="unit_price" step="0.01" id="unit_price" class="w-full mt-1 p-2 border rounded shadow-sm dark:bg-gray-700 dark:text-white" required>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Save Material</button>
                <a href="{{ route('raw_materials.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboardappearance>
