@extends('components.dashboard')
@section('title', 'Checkout')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Checkout</h1>
           
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <form action="{{ route('checkout.place') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Delivery Location</label>
                <input type="text" name="location" id="location" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Enter your delivery address" required>
            </div>

            <div>
                <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                <input type="text" name="mobile" id="mobile" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Enter your mobile number" required>
            </div>

            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-gray-700">Order Total:</span>
                    <span class="text-xl font-bold text-green-600">
                        UGX {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart))) }}
                    </span>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                    Place Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection