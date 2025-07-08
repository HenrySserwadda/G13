@extends('components.dashboard')

@section('page-title', 'Workforce Distribution Management')
@section('page-description', 'Manage workforce distribution across supply centers')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Workforce Distribution</h2>
        
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Add New Distribution</h3>
            <form action="{{ route('workforce.distribution.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="supply_center_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supply Center</label>
                        <select name="supply_center_id" id="supply_center_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }} ({{ $center->location }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="employee_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee Count</label>
                        <input type="number" name="employee_count" id="employee_count" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Required Skills</label>
                        <input type="text" name="skills" id="skills" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label for="shift" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shift</label>
                        <select name="shift" id="shift" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="morning">Morning</option>
                            <option value="afternoon">Afternoon</option>
                            <option value="night">Night</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Add Distribution
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Current Distribution</h3>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supply Center</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employees</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Skills</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Shift</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($centers as $center)
                        @foreach($center->workforce as $distribution)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $center->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $center->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $distribution->employee_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $distribution->skills }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ ucfirst($distribution->shift) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection