@extends('components.dashboard')
@section('title', 'Your Cart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Your Cart</h1>
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">{{ session('error') }}</div>
    @endif
    @if(empty($cart))
        <p>Your cart is empty.</p>
    @else
        <table class="w-full mb-6">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price (UGX)</th>
                    <th>Quantity</th>
                    <th>Available</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                    @php
                        $product = $products[$id] ?? null;
                        $available = $product ? $product->quantity : 0;
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($item['price']) }}</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="inline">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $available }}" class="w-16">
                                <button type="submit" class="ml-2 text-blue-600">Update</button>
                            </form>
                        </td>
                        <td>{{ $available }}</td>
                        <td>{{ number_format($subtotal) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-right font-bold text-lg mb-4">Total: UGX {{ number_format($total) }}</div>
        <form action="{{ route('checkout.show') }}" method="GET">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Checkout</button>
        </form>
    @endif
</div>
@endsection 