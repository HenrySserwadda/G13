@extends('components.dashboard')
@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">{{ session('error') }}</div>
    @endif
    <form action="{{ route('checkout.place') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-2">Location</label>
            <input type="text" name="location" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-2">Mobile Number</label>
            <input type="text" name="mobile" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4 font-bold">
            Total: UGX {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart))) }}
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Place Order</button>
    </form>
</div>
@endsection 