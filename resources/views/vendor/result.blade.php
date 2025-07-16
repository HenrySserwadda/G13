@extends('guest-layout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
    @if($success)
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Application Submitted Successfully!</h2>
            <p class="text-gray-600">Your vendor application has been received and validated.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vendor Information</h3>
                <div class="space-y-3">
                    <p><span class="font-medium">Name:</span> {{ $vendor['name'] ?? 'N/A' }}</p>
                    <p><span class="font-medium">CEO:</span> {{ $vendor['CEO'] ?? 'N/A' }}</p>
                    <p><span class="font-medium">Email:</span> {{ $vendor['email'] ?? 'N/A' }}</p>
                    <p><span class="font-medium">Capital:</span> UGX {{ number_format($vendor['capital'] ?? 0) }}</p>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Validation Results</h3>
                @if($validation['valid'] ?? false)
                    <div class="bg-green-100 text-green-800 p-4 rounded-lg">
                        <p class="font-medium">All requirements met!</p>
                    </div>
                @else
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mb-4">
                        <p class="font-medium">Some requirements not met:</p>
                    </div>
                    <ul class="list-disc pl-5 space-y-2">
                        @foreach($reasons as $reason)
                            <li>{{ $reason }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @else
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Submission Failed</h2>
            <p class="text-gray-600">{{ $error }}</p>
        </div>
    @endif

    <div class="flex justify-center">
        <a href="{{ route('home') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            Return to Home
        </a>
    </div>
</div>
@endsection