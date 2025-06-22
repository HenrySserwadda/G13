<x-dashboardappearance>
    <x-slot name="rawmaterials">
        <li>
            <a href="{{ route('raw_materials.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Raw Materials</span>
            </a>
        </li>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Raw Materials</h2>

            @if (Auth::user()->category != 'supplier')
                <a href="{{ route('raw_materials.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 010 2h-5v5a1 1 0 01-2 0v-5H4a1 1 0 010-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Add New
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-900 shadow rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($materials as $material)
                        <tr>
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-100">{{ $material->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $material->type }}</td>
                            <td class="px-6 py-4">{{ number_format($material->quantity) }}</td>
                            <td class="px-6 py-4">UGX {{ number_format($material->unit_price) }}</td>
                            <td class="px-6 py-4">
                                {{ $material->user->name ?? 'Unknown' }}
                                <br>
                                <small class="text-gray-400">{{ $material->user->email ?? '' }}</small>
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                @if(Auth::user()->id === $material->user_id || Auth::user()->category === 'systemadmin')
                                    <a href="{{ route('raw_materials.edit', $material) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    @if(Auth::user()->category === 'systemadmin')
                                        <form action="{{ route('raw_materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Delete this material?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">No Actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-6 py-4 text-gray-500">No materials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $materials->links() }}
        </div>
    </div>
</x-dashboardappearance>
