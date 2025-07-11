@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Vendor Validation Results</h1>

        @if($error)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong class="font-bold">Error:</strong>
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @endif

        @if($success && $results)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <p class="text-blue-800 mb-2">Welcome, valued vendor! We appreciate your interest in partnering with us.</p>
                <p class="text-blue-700">We encourage honesty and transparency throughout this process, as it forms the foundation for a successful and lasting business relationship. We look forward to the possibility of doing great business together!</p>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reasons</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($results as $result)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $result['vendor'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($result['valid'] ?? false)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Valid
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ❌ Invalid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if(isset($result['reasons']) && is_array($result['reasons']) && count($result['reasons']) > 0)
                                        <ul class="list-disc list-inside text-red-600">
                                            @foreach($result['reasons'] as $reason)
                                                <li>{{ $reason }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-500">No issues found</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $result['visitDate'] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-gray-700">Your Status Will be Updated based on validation criteria and a successful Visit to the facility</p>
            </div>

            <!-- Success/Error Popup -->
            <div id="validationPopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        @php
                            $hasInvalid = collect($results)->contains('valid', false);
                        @endphp
                        
                        @if($hasInvalid)
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Validation Results</h3>
                            <p class="text-sm text-gray-500">Some vendors did not pass validation.<br>Thank you for your submission.</p>
                        @else
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Success!</h3>
                            <p class="text-sm text-gray-500">All vendors have been successfully validated.<br>Wait for confirmation email.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('insertpdf') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Back to form
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('validationPopup');
    if (popup) {
        popup.classList.remove('hidden');
        popup.classList.add('block');
        
        // Hide popup after 5 seconds
        setTimeout(() => {
            popup.classList.remove('block');
            popup.classList.add('hidden');
        }, 5000);
    }
});
</script>
@endsection
