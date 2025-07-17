{{-- filepath: resources/views/products/show.blade.php --}}
@extends('components.dashboard')

@section('title', $product->name . ' - DURABAG')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Back link -->
        <div class="mb-6">
            <a id="back-link" href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>
        <script>
        // Context-aware back button: if ?from=ml or ?from=recommendation, go to ML recommendations
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const backLink = document.getElementById('back-link');
            if (urlParams.get('from') === 'ml' || urlParams.get('from') === 'recommendation') {
                backLink.href = '/products'; // Use the correct ML recommendations route
                backLink.textContent = '← Back to Recommendations';
            }
        });
        </script>

        <!-- Modern Product Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700 max-w-4xl mx-auto flex flex-col md:flex-row animate-fadeIn relative group transition-all duration-300">
            <!-- Product Image -->
            <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 p-6">
                <div class="relative w-full">
                    @if($product->is_ml_generated)
                        <span class="absolute top-4 left-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold px-4 py-2 rounded-full z-10 shadow-lg">For You</span>
                    @endif
                    @if($product->image)
                        @if($product->is_ml_generated)
                            <img src="/{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-80 object-cover rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl" style="display: none;">
                                <i class="fas fa-image text-5xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-80 object-cover rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl" style="display: none;">
                                <i class="fas fa-image text-5xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl">
                            <i class="fas fa-image text-5xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Product Details -->
            <div class="md:w-1/2 p-8 flex flex-col justify-center">
                <h2 class="text-3xl font-extrabold mb-2 text-gray-800 dark:text-white flex items-center">
                    {{ $product->name }}
                </h2>
                <!-- Replace tags with description in main product card -->
                <div class="mb-4">
                    <span class="text-gray-600 dark:text-gray-300 text-base">{{ $product->description }}</span>
                </div>
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-8 flex items-center">
                    <i class="fas fa-tag mr-2"></i> UGX {{ number_format($product->price) }}
                </div>
                <!-- Action Buttons -->
                @auth
                    @if(Auth::user()->category !== 'systemadmin')
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl w-full flex items-center justify-center text-lg font-semibold shadow-lg transition-all duration-300">
                                <i class="fas fa-cart-plus mr-3"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <i class="fas fa-info-circle mr-1"></i> 
                                You are logged in as <strong>System Admin</strong>, cart not available.
                            </p>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a> to order this product.
                        </p>
                    </div>
                @endauth
                <!-- Admin Actions -->
                @auth
                    @if(Auth::user()->category === 'systemadmin')
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex space-x-3">
                            <a href="{{ route('products.edit', $product->id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                    <i class="fas fa-trash mr-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: none; } }
        .animate-fadeIn { animation: fadeIn 0.7s cubic-bezier(.4,0,.2,1) both; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(40px);} to { opacity: 1; transform: none; } }
        .slide-up { animation: slideUp 0.7s cubic-bezier(.4,0,.2,1) both; }
    </style>
    <script>
    // Animate recommendation cards as they enter the viewport
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.recommendation-card');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Stagger effect based on card index
                        const idx = parseInt(entry.target.dataset.index) || 0;
                        setTimeout(() => {
                            entry.target.classList.add('slide-up');
                            entry.target.classList.remove('opacity-0', 'translate-y-10');
                        }, idx * 120);
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            cards.forEach(card => observer.observe(card));
        } else {
            // Fallback: show all
            cards.forEach(card => {
                card.classList.add('slide-up');
                card.classList.remove('opacity-0', 'translate-y-10');
            });
        }
    });
    </script>

    {{-- Recommendations Section --}}
    <div class="container mx-auto px-4 py-8 mt-10">
        <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white flex items-center">
            <i class="fas fa-lightbulb mr-2 text-yellow-400"></i> You may also like
        </h3>
        @if(!empty($recommendations) && is_array($recommendations) && count($recommendations))
            <div class="flex overflow-x-auto gap-6 pb-2 md:grid md:grid-cols-2 lg:grid-cols-4 md:gap-6 md:overflow-x-visible recommendations-carousel">
                @foreach(array_slice($recommendations, 0, 8) as $i => $rec)
                    <div class="recommendation-card bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 flex flex-col min-w-[260px] max-w-xs mx-auto relative group opacity-0 translate-y-10"
                         data-index="{{ $i }}">
                        @if(!empty($rec['is_ml_generated']) && $rec['is_ml_generated'])
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full z-10 shadow-md">For You</span>
                        @endif
                        @if(!empty($rec['image']))
                            <img src="/{{ $rec['image'] }}" alt="{{ $rec['name'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center" style="display: none;">
                                <i class="fas fa-image text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-image text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @endif
                        <div class="p-4 flex-1 flex flex-col">
                            <h4 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white line-clamp-1">{{ $rec['name'] }}</h4>
                            <!-- Replace tags with description in recommendations -->
                            <div class="mb-2">
                                <span class="text-gray-600 dark:text-gray-300 text-sm line-clamp-1">{{ $rec['description'] }}</span>
                            </div>
                            <div class="text-blue-600 dark:text-blue-400 font-bold mb-2 text-lg">UGX {{ number_format($rec['price']) }}</div>
                            <div class="flex space-x-2 mt-auto">
                                <a href="{{ route('products.show', $rec['id']) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-lg text-sm flex items-center justify-center transition-colors duration-200 font-medium"><i class="fas fa-eye mr-1"></i> View</a>
                                @auth
                                    @if(Auth::user()->category !== 'systemadmin')
                                        <form action="{{ route('cart.add', $rec['id']) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg w-full flex items-center justify-center text-sm transition-colors duration-200 font-medium"><i class="fas fa-cart-plus mr-1"></i> Add</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <style>
                .recommendations-carousel::-webkit-scrollbar { height: 8px; background: #f3f4f6; }
                .recommendations-carousel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
                @media (max-width: 768px) {
                    .recommendations-carousel { flex-wrap: nowrap; overflow-x: auto; }
                }
                @keyframes fadeIn { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: none; } }
                .animate-fadeIn { animation: fadeIn 0.7s cubic-bezier(.4,0,.2,1) both; }
            </style>
        @else
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 text-center text-gray-500 dark:text-gray-400 mt-4">
                <i class="fas fa-box-open text-3xl mb-2"></i>
                <p>No related recommendations found for this product.</p>
            </div>
        @endif
    </div>
@endsection