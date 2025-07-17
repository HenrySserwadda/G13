@extends('components.dashboard')

@section('content')
<div class="container mx-auto p-4 space-y-8 max-w-6xl dark:bg-gray-900 dark:text-gray-200 transition-colors duration-300">
    <!-- Header Section -->
    <div class="flex flex-col mb-6">
        <!-- Centered Heading -->
        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Manage Supply Centers & Workers</h2>
        </div>
        
        <!-- Description and Toggle Row -->
        <div class="flex justify-between items-start">
            <!-- Left-aligned Description -->
            <p class="block text-sm font-medium text-gray-700 dark:text-gray-300 max-w-2xl mr-4">
                Optimize your Workforce Allocation where necessary, you can add Supply Centers or delete them and add new workers, delete some or change them to different supply centers
            </p>
            
            <!-- Right-aligned Toggle Button -->
            <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-2.5">
                <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- View Button -->
    <div class="flex justify-end">
        <a href="{{ route('workforce.index') }}" class="flex items-center bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-300 font-semibold px-6 py-3 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
            < VIEW
        </a>
    </div>

    @if (session('success'))
        <div id="success-message" x-data="{ show: true }" x-show="show" class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-100 p-4 rounded mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <p>{{ session('success') }}</p>
            <script>
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                }, 5000);
            </script>
        </div>
    @endif

    <!-- Add Supply Center Form -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Supply Center
        </h3>
        <form action="{{ route('supply-centers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Center Name</label>
                    <input type="text" name="name" placeholder="Enter center name" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                    <input type="text" name="location" placeholder="Enter location" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                Add Center
            </button>
        </form>
    </div>

    <!-- Supply Centers Table -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Supply Centers
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($centers as $center)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" x-data="{ editing: false }">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span x-show="!editing" class="dark:text-gray-200">{{ $center->name }}</span>
                            <input x-show="editing" type="text" name="name" value="{{ $center->name }}" 
                                   class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span x-show="!editing" class="dark:text-gray-200">{{ $center->location }}</span>
                            <input x-show="editing" type="text" name="location" value="{{ $center->location }}" 
                                   class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-3 py-1 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800" id="menu-button" aria-expanded="true" aria-haspopup="true">
                                        Actions
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 dark:ring-gray-600 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                    <div class="py-1" role="none">
                                        <button x-show="!editing" @click="editing = true; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem" tabindex="-1">
                                            Edit
                                        </button>
                                        <form x-show="editing" action="{{ route('supply-centers.update', $center) }}" method="POST" class="block w-full text-left">
                                            @csrf @method('PUT')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem" tabindex="-1">
                                                Save
                                            </button>
                                        </form>
                                        <form action="{{ route('supply-centers.destroy', $center) }}" method="POST" class="block w-full text-left">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this center?')" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-red-900 dark:hover:text-red-300" role="menuitem" tabindex="-1">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Worker Form -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Add New Worker
        </h3>
        <form action="{{ route('workers.store') }}" method="POST" class="space-y-4">   @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Worker Name</label>
                    <input type="text" name="name" placeholder="Enter worker name" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign to Center</label>
                    <select name="supply_center_id" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Unassigned</option>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                Add Worker
            </button>
        </form>
    </div>

    <!-- Workers Table with Show More/Less Functionality -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700"
         x-data="{
            showAll: false,
            workers: {{ Js::from($workers) }},
            get visibleWorkers() {
                return this.showAll ? this.workers : this.workers.slice(0, 8);
            },
            toggleShowAll() {
                this.showAll = !this.showAll;
            }
         }">

        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Workers
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supply Center</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($workers as $i => $worker)
                    <tr x-show="showAll || {{ $i }} < 8" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap align-top">
                            <div>
                                <div>{{ $worker->name }}</div>
                                <div x-data="{ showTransfer: false, justTransferred: false }" class="mt-2">
                                    <button @click="showTransfer = !showTransfer" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mb-2">
                                        Transfer
                                    </button>
                                    <div x-show="showTransfer" class="mt-2">
                                        <form action="{{ route('workers.allocate') }}" method="POST" class="space-y-2" @submit="justTransferred = true; setTimeout(() => { showTransfer = false; justTransferred = false; }, 2000)">
                                            @csrf
                                            <input type="hidden" name="worker_id" value="{{ $worker->id }}">
                                            <select name="to_center_id" required class="border rounded-lg px-3 py-2 text-xs w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-150 ease-in-out bg-white dark:bg-gray-800 dark:text-gray-200">
                                                <option value="">Select Center</option>
                                    @foreach ($centers as $center)
                                                    <option value="{{ $center->id }}" {{ $worker->supply_center_id == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                    @endforeach
                                </select>
                                            <input type="text" name="reason" placeholder="Reason for transfer" required class="border rounded px-2 py-1 w-full" />
                                            <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs flex items-center justify-center gap-1 hover:bg-green-700 w-fit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                                Submit
                                            </button>
                                        </form>
                                        <div x-show="justTransferred" class="mt-2 flex items-center text-green-600">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Transfer successful!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap align-top">{{ $worker->supplyCenter->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top">
                            <div x-data="{ showOptions: false, editing: false, name: '{{ $worker->name }}' }" class="flex flex-col items-end gap-2">
                                <button @click="showOptions = !showOptions" class="bg-green-600 text-white px-3 py-1 rounded text-xs flex items-center gap-1 hover:bg-green-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                    Select
                                </button>
                                <div x-show="showOptions" class="mt-2 flex flex-col items-end gap-2">
                                    <template x-if="!editing">
                                        <button @click="editing = true" class="bg-yellow-400 text-white px-2 py-1 rounded text-xs flex items-center gap-1 hover:bg-yellow-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 17v4h4l10.293-10.293a1 1 0 00-1.414-1.414L3 17z" />
                                            </svg>
                                            Edit
                                        </button>
                                    </template>
                                    <template x-if="editing">
                                        <form action="{{ route('workers.update', $worker->id) }}" method="POST" class="flex items-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" x-model="name" class="border rounded px-2 py-1 text-xs" required>
                                            <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs flex items-center gap-1 hover:bg-green-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                    Save
                                                </button>
                                            <button type="button" @click="editing = false" class="bg-gray-400 text-white px-2 py-1 rounded text-xs hover:bg-gray-500">Cancel</button>
                                            </form>
                                    </template>
                                    <form action="{{ route('workers.destroy', $worker->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this worker?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-xs flex items-center gap-1 hover:bg-red-600 mt-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                                    Delete
                                                </button>
                                            </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Show More/Less Button -->
        <div class="mt-4 text-center" x-show="workers.length > 8">
            <button @click="toggleShowAll()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                <span x-text="showAll ? 'Show Less' : 'Show More'"></span>
                <span x-text="`(${showAll ? workers.length : workers.length - 8})`"></span>
            </button>
        </div>
    </div>
</div>

<!-- Alpine JS for the interactive components -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Dark Mode Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // If set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                // If NOT set via local storage previously
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    });
</script>

<style>
    /* Custom scrollbar for dark mode */
    .dark ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .dark ::-webkit-scrollbar-track {
        background: #374151;
    }
    .dark ::-webkit-scrollbar-thumb {
        background: #6b7280;
        border-radius: 4px;
    }
    .dark ::-webkit-scrollbar-thumb:hover {
        background: #7c7f85ff;
    }

    /* Smooth transitions for dark mode */
    .dark * {
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    /* Table row hover effects in dark mode */
    .dark tr:hover {
        background-color: rgba(190, 215, 255, 0.5) !important;
    }
</style>
@endsection