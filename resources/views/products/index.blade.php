{{-- filepath: resources/views/products/index.blade.php --}}
@extends('components.dashboard')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Products - DURABAG')
@section('page-title', 'Products')
@section('page-description', 'Browse and manage all available products in the shop.')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 300px;
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
        .no-image-placeholder {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }
        .dark .no-image-placeholder {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(40px);} to { opacity: 1; transform: none; } }
        .slide-up { animation: slideUp 0.7s cubic-bezier(.4,0,.2,1) both; }
        #cart-toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }
    </style>
@endpush

@section('content')
<script>
    window.LaravelCartAddRoute = '{{ route('cart.add', ['product' => 'PRODUCT_ID']) }}';
    window.LaravelCsrfToken = '{{ csrf_token() }}';
</script>
<div class="container mx-auto px-4 py-8">
    <script>
        window.LaravelUserCategory = @json(Auth::user()->category ?? null);
    </script>

    <div class="mb-8">
        <div class="flex space-x-2">
            <button id="tab-products" class="tab-btn bg-blue-600 text-white px-4 py-2 rounded-t focus:outline-none">All Products</button>
            @auth
                @if(Auth::user()->category === 'wholesaler' || Auth::user()->category === 'retailer')
                    <button id="tab-recommendations" class="tab-btn bg-gray-200 text-gray-800 px-4 py-2 rounded-t focus:outline-none">Recommended for You</button>
                @endif
            @endauth
        </div>
    </div>

    <div id="products-section">
        @auth
            @if(Auth::user()->category === 'wholesaler' || Auth::user()->category === 'retailer')
                <div class="mb-2 text-gray-700 font-semibold text-sm">What could you be interested in?</div>
                <div id="all-products-tag-bar-loader" class="hidden flex justify-center items-center py-2">
                    <div class="animate-spin rounded-full h-8 w-8 border-t-4 border-blue-500 border-opacity-50"></div>
                </div>
                <div id="all-products-tag-bar" class="flex overflow-x-auto space-x-2 py-2 mb-6"></div>
            @endif
        @endauth
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-shopping-bag mr-3"></i> Our Bag Collection
            </h1>
            @auth
                @if(Auth::user()->category === 'systemadmin')
                    <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center shadow-lg transition-all duration-300 hover:shadow-xl">
                        <i class="fas fa-plus-circle mr-2"></i> Add New Product
                    </a>
                @endif
            @endauth
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 dark:bg-green-900 dark:border-green-700 dark:text-green-100">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="product-card recommendation-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl opacity-0 translate-y-10" data-index="{{ $loop->index }}">
                    <div class="product-image-container">
                        @if($product->image)
                            @php
                                $isMlImage = Str::startsWith($product->image, 'images/dataset/');
                                $imgSrc = $isMlImage ? asset($product->image) : asset('storage/'.$product->image);
                            @endphp
                            <img src="{{ $imgSrc }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 srcset="{{ $imgSrc }} 1x, {{ $imgSrc }} 2x">
                            <div class="no-image-placeholder" style="display: none;">
                                <i class="fas fa-camera text-5xl text-gray-400"></i>
                            </div>
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
                            <a href="{{ route('products.show', $product->id) }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-300 flex-1 justify-center">
                                <i class="fas fa-eye mr-2"></i> View
                            </a>

                            @auth
                                @if(Auth::user()->category === 'wholesaler' || Auth::user()->category === 'retailer')
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1 ajax-add-to-cart">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full flex items-center justify-center transition-colors duration-300">
                                            <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                                        </button>
                                    </form>
                                @endif

                                @if(Auth::user()->category === 'systemadmin')
                                    <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors duration-300 flex-1">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-500 text-xl">
                        <i class="fas fa-box-open text-5xl mb-4"></i>
                        <p class="text-2xl font-light">No products available yet</p>
                        @auth
                            @if(Auth::user()->category === 'systemadmin')
                                <a href="{{ route('products.create') }}" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg">
                                    <i class="fas fa-plus-circle mr-2"></i> Create First Product
                                </a>
                            @endif
                        @endauth
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

    @auth
        @if(Auth::user()->category === 'wholesaler' || Auth::user()->category === 'retailer')
            <div id="recommendations-section" class="hidden">
                @include('ml.recommendations')
            </div>
        @endif
    @endauth
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Slide-up animation for product cards
    const cards = document.querySelectorAll('.product-card.recommendation-card');
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const i = parseInt(entry.target.getAttribute('data-index')) || 0;
                setTimeout(() => {
                    entry.target.classList.add('slide-up');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                }, i * 120);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    cards.forEach(card => observer.observe(card));

    // Function to update all cart badges
    function updateCartBadges(count) {
        const badges = document.querySelectorAll('#cart-count-badge');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-flex' : 'none';
        });
    }

    // AJAX Add to Cart with immediate UI update
    document.addEventListener('submit', async function(e) {
        if (e.target.matches('.ajax-add-to-cart')) {
            e.preventDefault();
            const form = e.target;
            const button = form.querySelector('button');
            const originalText = button.innerHTML;
            
            // Save current scroll position
            const scrollPosition = window.scrollY || window.pageYOffset;
            
            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';

            try {
                // Get current cart count from existing badge
                const currentCount = parseInt(document.querySelector('#cart-count-badge')?.textContent) || 0;
                
                // Optimistically update UI immediately
                updateCartBadges(currentCount + 1);
                
                // Make the API call
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.LaravelCsrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(form)
                });
                
                const data = await response.json();
                
                if (data && data.success) {
                    showToast(data.success);
                    // Update with accurate count from server if provided
                    if (data.count !== undefined) {
                        updateCartBadges(data.count);
                    }
                } else {
                    // Revert if server indicates failure
                    updateCartBadges(currentCount);
                }
            } catch (e) {
                console.error('Add to cart failed:', e);
                // On error, revert to previous count
                const currentCount = parseInt(document.querySelector('#cart-count-badge')?.textContent) || 0;
                updateCartBadges(Math.max(0, currentCount - 1));
            } finally {
                // Restore button state
                button.disabled = false;
                button.innerHTML = originalText;
                
                // Restore scroll position
                window.scrollTo(0, scrollPosition);
            }
        }
    });

    // Toast notification function
    function showToast(msg) {
        if (!document.getElementById('cart-toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'cart-toast-container';
            document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = 'bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center mb-2';
        toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + msg;
        document.getElementById('cart-toast-container').appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = 0;
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Tab switching logic
    const tabProducts = document.getElementById('tab-products');
    const tabRecs = document.getElementById('tab-recommendations');
    const productsSection = document.getElementById('products-section');
    const recsSection = document.getElementById('recommendations-section');

    if (tabProducts && tabRecs) {
        tabProducts.addEventListener('click', function() {
            tabProducts.classList.add('bg-blue-600', 'text-white');
            tabProducts.classList.remove('bg-gray-200', 'text-gray-800');
            tabRecs.classList.remove('bg-blue-600', 'text-white');
            tabRecs.classList.add('bg-gray-200', 'text-gray-800');
            productsSection.classList.remove('hidden');
            if (recsSection) recsSection.classList.add('hidden');
        });

        tabRecs.addEventListener('click', function() {
            tabRecs.classList.add('bg-blue-600', 'text-white');
            tabRecs.classList.remove('bg-gray-200', 'text-gray-800');
            tabProducts.classList.remove('bg-blue-600', 'text-white');
            tabProducts.classList.add('bg-gray-200', 'text-gray-800');
            productsSection.classList.add('hidden');
            if (recsSection) recsSection.classList.remove('hidden');
        });
    }

    // ML Tag Bar Logic
    let allProducts = [];
    let allTags = { colors: [], styles: [] };
    let selectedTags = { color: null, style: null };

    const tagBarLoader = document.getElementById('all-products-tag-bar-loader');
    const tagBar = document.getElementById('all-products-tag-bar');

    async function fetchTagsAndProducts() {
        tagBarLoader.classList.remove('hidden');
        tagBar.classList.add('opacity-50', 'pointer-events-none');
        const res = await fetch('/api/products-for-ml');
        const data = await res.json();
        allProducts = data.products || [];
        // Extract unique colors and styles from all products (use ml_color and ml_style)
        const colors = [...new Set(allProducts.map(p => p.ml_color).filter(Boolean))];
        const styles = [...new Set(allProducts.map(p => p.ml_style).filter(Boolean))];
        allTags = { colors, styles };
        renderTagBar(colors, styles, allProducts);
        tagBarLoader.classList.add('hidden');
        tagBar.classList.remove('opacity-50', 'pointer-events-none');
    }

    function renderTagBar(colors, styles, products) {
        tagBar.innerHTML = '';
        // Render color tags
        colors.forEach(color => {
            const product = products.find(p => p.ml_color === color);
            const img = product ? product.image : '';
            let imgSrc = '';
            if (img) {
                imgSrc = img.startsWith('images/dataset/') ? `/${img}` : `/storage/${img}`;
            }
            const tag = document.createElement('div');
            tag.className = 'tag-item cursor-pointer rounded-lg px-4 py-2 flex items-center bg-green-200 hover:bg-green-300';
            tag.onclick = () => { selectedTags.color = color; selectedTags.style = null; filterProducts(); highlightSelectedTags(); };
            tag.innerHTML = imgSrc ? `<img src="${imgSrc}" class="w-8 h-8 rounded-full mr-2" alt="${color}" onerror="this.style.display='none'"/><span>${color}</span>` : `<span>${color}</span>`;
            tag.dataset.type = 'color';
            tag.dataset.value = color;
            tagBar.appendChild(tag);
        });
        // Render style tags
        styles.forEach(style => {
            const product = products.find(p => p.ml_style === style);
            const img = product ? product.image : '';
            let imgSrc = '';
            if (img) {
                imgSrc = img.startsWith('images/dataset/') ? `/${img}` : `/storage/${img}`;
            }
            const tag = document.createElement('div');
            tag.className = 'tag-item cursor-pointer rounded-lg px-4 py-2 flex items-center bg-blue-200 hover:bg-blue-300';
            tag.onclick = () => { selectedTags.style = style; selectedTags.color = null; filterProducts(); highlightSelectedTags(); };
            tag.innerHTML = imgSrc ? `<img src="${imgSrc}" class="w-8 h-8 rounded-full mr-2" alt="${style}" onerror="this.style.display='none'"/><span>${style}</span>` : `<span>${style}</span>`;
            tag.dataset.type = 'style';
            tag.dataset.value = style;
            tagBar.appendChild(tag);
        });
    }

    function filterProducts() {
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.querySelector('h2').textContent;
            const desc = card.querySelector('p').textContent;
            let show = true;
            if (selectedTags.color) {
                show = desc.includes(selectedTags.color);
            } else if (selectedTags.style) {
                show = desc.includes(selectedTags.style);
            }
            card.style.display = show ? '' : 'none';
        });
    }

    function highlightSelectedTags() {
        document.querySelectorAll('.tag-item').forEach(tag => {
            const type = tag.dataset.type;
            const value = tag.dataset.value;
            const isColor = type === 'color';
            const isStyle = type === 'style';

            if (isColor) {
                tag.classList.toggle('bg-green-500', value === selectedTags.color);
                tag.classList.toggle('text-white', value === selectedTags.color);
            } else if (isStyle) {
                tag.classList.toggle('bg-blue-500', value === selectedTags.style);
                tag.classList.toggle('text-white', value === selectedTags.style);
            }
        });
    }

    // Initial fetch and render
    fetchTagsAndProducts();

    // Add event listeners for tag bar items
    tagBar.addEventListener('click', function(e) {
        const tag = e.target.closest('.tag-item');
        if (tag) {
            const type = tag.dataset.type;
            const value = tag.dataset.value;

            if (type === 'color') {
                selectedTags.color = value;
                selectedTags.style = null;
            } else if (type === 'style') {
                selectedTags.style = value;
                selectedTags.color = null;
            }
            filterProducts();
            highlightSelectedTags();
        }
    });

    // Clear filters button
    const clearFiltersBtn = document.createElement('button');
    clearFiltersBtn.className = 'bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg ml-2';
    clearFiltersBtn.innerHTML = '<i class="fas fa-times-circle mr-2"></i> Clear Filters';
    clearFiltersBtn.onclick = () => {
        selectedTags = { color: null, style: null };
        filterProducts();
        highlightSelectedTags();
    };
    document.getElementById('all-products-tag-bar').appendChild(clearFiltersBtn);
});
</script>
@endpush
@endsection