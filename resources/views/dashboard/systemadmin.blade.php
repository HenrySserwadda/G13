@extends('components.dashboard')

@section('page-title', 'System Admin Dashboard')
@section('page-description', 'Overview of your system admin dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <script>
        window.LaravelUserCategory = @json(Auth::user()->category ?? null);
        window.LaravelCsrfToken = '{{ csrf_token() }}';
        window.LaravelCartAddRoute = '{{ route('cart.add', ['product' => 'PRODUCT_ID']) }}';
    </script>
    <div class="mb-8">
        <div class="flex space-x-2">
            <button id="tab-products" class="tab-btn bg-blue-600 text-white px-4 py-2 rounded-t focus:outline-none">All Products</button>
            <button id="tab-recommendations" class="tab-btn bg-gray-200 text-gray-800 px-4 py-2 rounded-t focus:outline-none">Recommended for You</button>
        </div>
    </div>
    <div id="products-section">
        <div class="mb-2 text-gray-700 font-semibold text-sm">What could you be interested in?</div>
        @if(!in_array(Auth::user()->category, ['systemadmin', 'staff']))
        <div id="all-products-tag-bar-loader" class="hidden flex justify-center items-center py-2">
            <div class="animate-spin rounded-full h-8 w-8 border-t-4 border-blue-500 border-opacity-50"></div>
        </div>
        <div id="all-products-tag-bar" class="flex overflow-x-auto space-x-2 py-2 mb-6"></div>
        @endif
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-shopping-bag mr-3"></i> Our Bag Collection
            </h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="product-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl">
                    <div class="product-image-container">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-camera text-5xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>
                            <div class="text-lg font-bold text-blue-600">UGX {{ number_format($product->price) }}</div>
                        </div>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $product->description }}</p>
                        <div class="flex space-x-3">
                            <a href="{{ route('products.show', $product->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-300 flex-1 justify-center">
                                <i class="fas fa-eye mr-2"></i> View
                            </a>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full flex items-center justify-center transition-colors duration-300">
                                    <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-500 text-xl">
                        <i class="fas fa-box-open text-5xl mb-4"></i>
                        <p class="text-2xl font-light">No products available yet</p>
                    </div>
                </div>
            @endforelse
        </div>
        @if($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
    <div id="recommendations-section" class="hidden">
        @include('ml.recommendations')
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabProducts = document.getElementById('tab-products');
    const tabRecs = document.getElementById('tab-recommendations');
    const productsSection = document.getElementById('products-section');
    const recsSection = document.getElementById('recommendations-section');
    if (tabProducts) {
        tabProducts.addEventListener('click', function() {
            tabProducts.classList.add('bg-blue-600', 'text-white');
            tabProducts.classList.remove('bg-gray-200', 'text-gray-800');
            if (tabRecs) {
                tabRecs.classList.remove('bg-blue-600', 'text-white');
                tabRecs.classList.add('bg-gray-200', 'text-gray-800');
            }
            productsSection.classList.remove('hidden');
            if (recsSection) recsSection.classList.add('hidden');
        });
    }
    if (tabRecs) {
        tabRecs.addEventListener('click', function() {
            tabRecs.classList.add('bg-blue-600', 'text-white');
            tabRecs.classList.remove('bg-gray-200', 'text-gray-800');
            tabProducts.classList.remove('bg-blue-600', 'text-white');
            tabProducts.classList.add('bg-gray-200', 'text-gray-800');
            productsSection.classList.add('hidden');
            if (recsSection) recsSection.classList.remove('hidden');
        });
    }
    // Tag bar for All Products section
    let allProductsTags = { colors: [], styles: [] };
    let allProductsTagBar = document.getElementById('all-products-tag-bar');
    async function fetchAllProductsTags() {
        document.getElementById('all-products-tag-bar-loader').classList.remove('hidden');
        allProductsTagBar.classList.add('opacity-50', 'pointer-events-none');
        const res = await fetch('/api/products-for-ml');
        const data = await res.json();
        const products = data.products || [];
        const colors = [...new Set(products.map(p => p.color).filter(Boolean))];
        const styles = [...new Set(products.map(p => p.style).filter(Boolean))];
        allProductsTags = { colors, styles };
        renderAllProductsTagBar(colors, styles, products);
        document.getElementById('all-products-tag-bar-loader').classList.add('hidden');
        allProductsTagBar.classList.remove('opacity-50', 'pointer-events-none');
    }
    function renderAllProductsTagBar(colors, styles, products) {
        allProductsTagBar.innerHTML = '';
        colors.forEach(color => {
            const product = products.find(p => p.color === color);
            const img = product ? product.image : '';
            const tag = document.createElement('div');
            tag.className = 'tag-item cursor-pointer rounded-lg px-4 py-2 flex items-center bg-green-200 hover:bg-green-300';
            tag.onclick = () => handleAllProductsTagClick('color', color);
            tag.innerHTML = `<img src="/${img}" class="w-8 h-8 rounded-full mr-2" alt="${color}"><span>${color}</span>`;
            allProductsTagBar.appendChild(tag);
        });
        styles.forEach(style => {
            const product = products.find(p => p.style === style);
            const img = product ? product.image : '';
            const tag = document.createElement('div');
            tag.className = 'tag-item cursor-pointer rounded-lg px-4 py-2 flex items-center bg-blue-200 hover:bg-blue-300';
            tag.onclick = () => handleAllProductsTagClick('style', style);
            tag.innerHTML = `<img src="/${img}" class="w-8 h-8 rounded-full mr-2" alt="${style}"><span>${style}</span>`;
            allProductsTagBar.appendChild(tag);
        });
    }
    function handleAllProductsTagClick(type, value) {
        const tabRecs = document.getElementById('tab-recommendations');
        if (tabRecs) tabRecs.click();
        setTimeout(() => {
            if (window.selectedTags) {
                if (type === 'color') {
                    window.selectedTags.color = value;
                } else if (type === 'style') {
                    window.selectedTags.style = value;
                }
            }
            if (typeof window.fetchRecommendations === 'function') {
                window.fetchRecommendations();
            }
        }, 100);
    }
    fetchAllProductsTags();
});
</script>
<style>
.tab-btn { transition: background 0.2s, color 0.2s; }
.tab-btn.bg-blue-600 { border-bottom: 2px solid #2563eb; }
</style>
@endsection