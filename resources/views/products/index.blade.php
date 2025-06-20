@extends('dashboard') {{-- or your main layout file --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Popular Categories Section -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Popular Categories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
            <h3 class="font-semibold text-gray-700">BACKPACKS</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
            <h3 class="font-semibold text-gray-700">HANDBAGS</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
            <h3 class="font-semibold text-gray-700">TRAVEL BAGS</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
            <h3 class="font-semibold text-gray-700">LAPTOP BAGS</h3>
        </div>
    </div>

    <!-- Trending Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">TRENDING</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Featured Product 1 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-gray-100 h-48 flex items-center justify-center">
                    <span class="text-gray-500">Premium Leather Backpack Image</span>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">Premium Leather Backpack - Waterproof</h3>
                    <p class="text-red-600 font-bold text-xl mb-4">USX 1,200,000</p>
                    <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors">
                        ADD TO CART
                    </button>
                </div>
            </div>
            
            <!-- Featured Product 2 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-gray-100 h-48 flex items-center justify-center">
                    <span class="text-gray-500">Designer Handbag Image</span>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">Designer Luxury Handbag - Limited Edition</h3>
                    <p class="text-red-600 font-bold text-xl mb-4">USX 2,800,000</p>
                    <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors">
                        ADD TO CART
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Section -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">SALE</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sale Product 1 -->
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow relative">
            <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
            <div class="bg-gray-100 h-48 flex items-center justify-center">
                <span class="text-gray-500">Business Briefcase Image</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-2">Executive Leather Briefcase - 15" Laptop</h3>
                <p class="text-red-600 font-bold text-xl mb-1">USX 1,800,000 <span class="text-gray-500 text-sm line-through ml-2">USX 2,400,000</span></p>
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors mt-3">
                    ADD TO CART
                </button>
            </div>
        </div>
        
        <!-- Sale Product 2 -->
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow relative">
            <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
            <div class="bg-gray-100 h-48 flex items-center justify-center">
                <span class="text-gray-500">Travel Duffel Bag Image</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-2">XL Travel Duffel Bag - Water Resistant</h3>
                <p class="text-red-600 font-bold text-xl mb-1">USX 1,500,000 <span class="text-gray-500 text-sm line-through ml-2">USX 2,100,000</span></p>
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors mt-3">
                    ADD TO CART
                </button>
            </div>
        </div>
        
        <!-- Sale Product 3 -->
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow relative">
            <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
            <div class="bg-gray-100 h-48 flex items-center justify-center">
                <span class="text-gray-500">Crossbody Bag Image</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-2">Women's Crossbody Bag - Anti-Theft Design</h3>
                <p class="text-red-600 font-bold text-xl mb-1">USX 900,000 <span class="text-gray-500 text-sm line-through ml-2">USX 1,300,000</span></p>
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors mt-3">
                    ADD TO CART
                </button>
            </div>
        </div>
    </div>

    <!-- New Arrivals Section -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">NEW ARRIVALS</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- New Product 1 -->
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gray-100 h-40 flex items-center justify-center">
                <span class="text-gray-500">Mini Backpack Image</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-md mb-2">Urban Mini Backpack - USB Charging Port</h3>
                <p class="text-red-600 font-bold text-lg mb-3">USX 950,000</p>
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors text-sm">
                    ADD TO CART
                </button>
            </div>
        </div>
        
        <!-- New Product 2 -->
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gray-100 h-40 flex items-center justify-center">
                <span class="text-gray-500">Tote Bag Image</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-md mb-2">Canvas Tote Bag - Eco Friendly</h3>
                <p class="text-red-600 font-bold text-lg mb-3">USX 650,000</p>
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors text-sm">
                    ADD TO CART
                </button>
            </div>
        </div>
        
        <!-- New Product 3 -->
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gray-100 h-40 flex items-center justify-center">
                <span class="text-gray-500">Messenger Bag Image</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-md mb-2">Vintage Messenger Bag - Genuine Leather</h3>
                <p class="text-red-600 font-bold text-lg mb-3">USX 1,100,000</p>
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors text-sm">
                    ADD TO CART
                </button>
            </div>
        </div>
        
        <!-- New Product 4 -->
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gray-100 h-40 flex items-center justify-center">
                <span class="text-gray-500">Waist Bag Image</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-md mb-2">Sport Waist Bag - Waterproof & Lightweight</h3>
                <p class="text-red-600 font-bold text-lg mb-3">USX 450,000</p>
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded transition-colors text-sm">
                    ADD TO CART
                </button>
            </div>
        </div>
    </div>
</div>
@endsection