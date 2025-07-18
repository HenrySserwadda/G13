<div class="container mx-auto py-4">
    <h1 class="text-2xl font-bold mb-4">Recommended Products</h1>

    <!-- Personalized Recommendations Section -->
    <div id="personalized-section" class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                Personalized for You
            </h2>
            <button id="refresh-personalized" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-300">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </button>
        </div>
        
        <div id="personalized-loader" class="hidden flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-50"></div>
        </div>
        
        <div id="personalized-recommendations" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6"></div>
        
        <div id="user-profile" class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold mb-3">Your Shopping Profile</h3>
            <div id="profile-content" class="text-sm text-gray-600">
                <div class="flex items-center mb-2">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Loading your profile...
                </div>
            </div>
        </div>
    </div>

    <!-- General Recommendations Section -->
    <div class="border-t pt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-th-large mr-2 text-green-600"></i>
            Explore All Products
        </h2>

        <!-- Gender Selection -->
        <div class="mb-4 flex items-center space-x-2">
            <label for="gender-select" class="font-semibold">Gender:</label>
            <select id="gender-select" class="border rounded px-2 py-1">
                <option value="">All</option>
            </select>
        </div>

        <!-- Loader for tag bar -->
        @php $category = $userCategory ?? (Auth::user()->category ?? null); @endphp
        @if(!in_array($category, ['systemadmin', 'staff']))
        <div id="tag-bar-loader" class="hidden flex justify-center items-center py-2">
            <div class="animate-spin rounded-full h-8 w-8 border-t-4 border-blue-500 border-opacity-50"></div>
        </div>
        <!-- Tag Bar -->
        <div id="tag-bar" class="flex overflow-x-auto space-x-2 py-2 mb-6"></div>
        @endif

        <!-- Recommendations -->
        <div id="recommendations-loader" class="hidden flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-50"></div>
        </div>
        <div id="recommendations" class="grid grid-cols-1 md:grid-cols-3 gap-4"></div>
    </div>
</div>

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
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        .opacity-50 {
            opacity: 0.5;
        }
        .pointer-events-none {
            pointer-events: none;
        }
        .personalized-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
        }
        .profile-stat {
            display: inline-block;
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            margin: 4px;
            border: 1px solid #e5e7eb;
        }
        .profile-stat strong {
            color: #1f2937;
        }
        .for-you-sticker {
            position: absolute;
            top: 16px;
            left: 16px;
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 0.85rem;
            font-weight: bold;
            z-index: 20;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
@endpush

<script>
const tagBar = document.getElementById('tag-bar');
const recommendationsDiv = document.getElementById('recommendations');
const genderSelect = document.getElementById('gender-select');
const personalizedRecommendationsDiv = document.getElementById('personalized-recommendations');
const personalizedLoader = document.getElementById('personalized-loader');
const profileContent = document.getElementById('profile-content');
const refreshPersonalizedBtn = document.getElementById('refresh-personalized');

let selectedTags = { color: null, style: null };
let allTags = { colors: [], styles: [], genders: [] };
let selectedGender = '';
let allProducts = [];

// Load personalized recommendations
async function loadPersonalizedRecommendations() {
    try {
        personalizedLoader.classList.remove('hidden');
        personalizedRecommendationsDiv.classList.add('opacity-50', 'pointer-events-none');
        
        const res = await fetch('/ml/personalized-recommendations');
        const data = await res.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        renderPersonalizedRecommendations(data.products || []);
        loadUserProfile();
        
    } catch (error) {
        console.error('Error loading personalized recommendations:', error);
        personalizedRecommendationsDiv.innerHTML = `
            <div class="col-span-full text-center py-8">
                <div class="text-gray-500">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p class="text-lg">Unable to load personalized recommendations</p>
                    <p class="text-sm mt-2">Please try again later</p>
                </div>
            </div>
        `;
    } finally {
        personalizedLoader.classList.add('hidden');
        personalizedRecommendationsDiv.classList.remove('opacity-50', 'pointer-events-none');
    }
}

// Load user profile
async function loadUserProfile() {
    try {
        const res = await fetch('/ml/user-profile');
        const data = await res.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        const preferences = data.preferences;
        const purchaseSummary = data.purchase_summary;
        
        let profileHtml = '';
        
        if (preferences.purchase_frequency > 0) {
            profileHtml += `
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div class="profile-stat">
                        <strong>${preferences.purchase_frequency}</strong><br>
                        <span class="text-xs">Orders</span>
                    </div>
                    <div class="profile-stat">
                        <strong>UGX ${Number(purchaseSummary.total_spent).toLocaleString()}</strong><br>
                        <span class="text-xs">Total Spent</span>
                    </div>
                    <div class="profile-stat">
                        <strong>UGX ${Number(purchaseSummary.average_order_value).toLocaleString()}</strong><br>
                        <span class="text-xs">Avg Order</span>
                    </div>
                    <div class="profile-stat">
                        <strong>${preferences.gender || 'Not specified'}</strong><br>
                        <span class="text-xs">Gender</span>
                    </div>
                </div>
            `;
            
            if (preferences.preferred_styles.length > 0) {
                profileHtml += `
                    <div class="mb-3">
                        <strong class="text-gray-700">Preferred Styles:</strong>
                        <div class="flex flex-wrap gap-2 mt-1">
                            ${preferences.preferred_styles.map(style => 
                                `<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">${style}</span>`
                            ).join('')}
                        </div>
                    </div>
                `;
            }
            
            if (preferences.preferred_colors.length > 0) {
                profileHtml += `
                    <div class="mb-3">
                        <strong class="text-gray-700">Preferred Colors:</strong>
                        <div class="flex flex-wrap gap-2 mt-1">
                            ${preferences.preferred_colors.map(color => 
                                `<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">${color}</span>`
                            ).join('')}
                        </div>
                    </div>
                `;
            }
        } else {
            profileHtml = `
                <div class="text-center py-4">
                    <i class="fas fa-shopping-bag text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">No purchase history yet</p>
                    <p class="text-sm text-gray-500 mt-1">Start shopping to get personalized recommendations!</p>
                </div>
            `;
        }
        
        profileContent.innerHTML = profileHtml;
        
    } catch (error) {
        console.error('Error loading user profile:', error);
        profileContent.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle text-3xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">Unable to load profile</p>
            </div>
        `;
    }
}

function renderPersonalizedRecommendations(products) {
    personalizedRecommendationsDiv.innerHTML = '';
    
    if (!products.length) {
        personalizedRecommendationsDiv.innerHTML = `
            <div class="col-span-full text-center py-8">
                <div class="text-gray-500">
                    <i class="fas fa-user-slash text-4xl mb-4"></i>
                    <p class="text-lg">No personalized recommendations available</p>
                    <p class="text-sm mt-2">Try exploring all products below</p>
                </div>
            </div>
        `;
        return;
    }
    
    products.forEach((product, i) => {
        if (!product || typeof product.id === 'undefined') return; // skip invalid
        const hasImage = product.image && product.image !== 'images/dataset/none';
        let card = `<div class="product-card recommendation-card opacity-0 translate-y-10 bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl relative" data-index="${i}">`;
        
        // Add personalized badge
        if (product.is_personalized) {
            card += `<div class="personalized-badge"><i class="fas fa-star mr-1"></i>For You</div>`;
        }
        
        card += `<div class="product-image-container">`;
        // Remove the orange FOR YOU sticker on the image itself
        // if (product.is_personalized) {
        //     card += `<div class=\"for-you-sticker\">FOR YOU</div>`;
        // }
        if (hasImage) {
            card += `<img src="/${product.image}" alt="${product.name}" class="product-image" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`;
            card += `<div class="no-image-placeholder" style="display: none;"><i class="fas fa-camera text-5xl text-gray-400"></i></div>`;
        } else {
            card += `<div class="no-image-placeholder"><i class="fas fa-camera text-5xl text-gray-400"></i></div>`;
        }
        card += `</div>`;
        card += `<div class="p-5">`;
        card += `<div class="flex justify-between items-start">`;
        card += `<h2 class="text-xl font-bold text-gray-800 mb-2">${product.name}</h2>`;
        card += `<div class="text-lg font-bold text-blue-600">UGX ${Number(product.price).toLocaleString()}</div>`;
        card += `</div>`;
        card += `<p class="text-gray-600 mb-4 line-clamp-2">${product.description}</p>`;
        card += `<div class="flex space-x-3">`;
        card += `<a href="/products/${product.id}?from=ml" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-300 flex-1 justify-center"><i class="fas fa-eye mr-2"></i> View</a>`;
        let formAction = '#';
        if (typeof window.LaravelCartAddRoute === 'string' && (typeof product.id === 'string' || typeof product.id === 'number')) {
            formAction = window.LaravelCartAddRoute.replace('PRODUCT_ID', product.id);
        }
        card += `<form action="${formAction}" method="POST" class="flex-1 ajax-add-to-cart">`;
        card += `<input type="hidden" name="_token" value="${window.LaravelCsrfToken}">`;
        card += `<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full flex items-center justify-center transition-colors duration-300"><i class="fas fa-cart-plus mr-2"></i> Add to Cart</button>`;
        card += `</form>`;
        card += `</div>`;
        card += `</div>`;
        card += `</div>`;
        personalizedRecommendationsDiv.innerHTML += card;
    });
    animateRecommendationCards(personalizedRecommendationsDiv);
}

function renderRecommendations(products) {
    recommendationsDiv.innerHTML = '';
    if (!products.length) {
        recommendationsDiv.innerHTML = '<div class="col-span-full text-center py-12"><div class="text-gray-500 text-xl"><i class="fas fa-box-open text-5xl mb-4"></i><p class="text-2xl font-light">No recommendations found</p></div></div>';
        return;
    }
    products.forEach((product, i) => {
        if (!product || typeof product.id === 'undefined') return; // skip invalid
        const hasImage = product.image && product.image !== 'images/dataset/none';
        let card = `<div class="product-card recommendation-card opacity-0 translate-y-10 bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl" data-index="${i}">`;
        card += `<div class="product-image-container">`;
        if (hasImage) {
            card += `<img src="/${product.image}" alt="${product.name}" class="product-image" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`;
            card += `<div class="no-image-placeholder" style="display: none;"><i class="fas fa-camera text-5xl text-gray-400"></i></div>`;
        } else {
            card += `<div class="no-image-placeholder"><i class="fas fa-camera text-5xl text-gray-400"></i></div>`;
        }
        card += `</div>`;
        card += `<div class="p-5">`;
        card += `<div class="flex justify-between items-start">`;
        card += `<h2 class="text-xl font-bold text-gray-800 mb-2">${product.name}</h2>`;
        card += `<div class="text-lg font-bold text-blue-600">UGX ${Number(product.price).toLocaleString()}</div>`;
        card += `</div>`;
        card += `<p class="text-gray-600 mb-4 line-clamp-2">${product.description}</p>`;
        card += `<div class="flex space-x-3">`;
        card += `<a href="/products/${product.id}?from=ml" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-300 flex-1 justify-center"><i class="fas fa-eye mr-2"></i> View</a>`;
        let formAction = '#';
        if (typeof window.LaravelCartAddRoute === 'string' && (typeof product.id === 'string' || typeof product.id === 'number')) {
            formAction = window.LaravelCartAddRoute.replace('PRODUCT_ID', product.id);
        }
        card += `<form action="${formAction}" method="POST" class="flex-1 ajax-add-to-cart">`;
        card += `<input type="hidden" name="_token" value="${window.LaravelCsrfToken}">`;
        card += `<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full flex items-center justify-center transition-colors duration-300"><i class="fas fa-cart-plus mr-2"></i> Add to Cart</button>`;
        card += `</form>`;
        card += `</div>`;
        card += `</div>`;
        card += `</div>`;
        recommendationsDiv.innerHTML += card;
    });
    animateRecommendationCards(recommendationsDiv);
}

function animateRecommendationCards(container) {
    const cards = container.querySelectorAll('.recommendation-card');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
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
        cards.forEach(card => {
            card.classList.add('slide-up');
            card.classList.remove('opacity-0', 'translate-y-10');
        });
    }
}

// Fetch all products for tag extraction
async function fetchTagsAndProducts() {
    // Show tag bar loader
    document.getElementById('tag-bar-loader').classList.remove('hidden');
    tagBar.classList.add('opacity-50', 'pointer-events-none');

    const res = await fetch('/api/products-for-ml');
    const data = await res.json();
    allProducts = data.products || [];
    // Extract unique colors, styles, and genders from all products
    const colors = [...new Set(allProducts.map(p => p.color).filter(Boolean))];
    const styles = [...new Set(allProducts.map(p => p.style).filter(Boolean))];
    const genders = [...new Set(allProducts.map(p => p.gender).filter(Boolean))];
    allTags = { colors, styles, genders };
    renderGenderDropdown(genders);
    renderTagBar(colors, styles, allProducts);

    // Hide tag bar loader
    document.getElementById('tag-bar-loader').classList.add('hidden');
    tagBar.classList.remove('opacity-50', 'pointer-events-none');
    fetchRecommendations();
}

function renderGenderDropdown(genders) {
    genderSelect.innerHTML = '<option value="">All</option>';
    genders.forEach(gender => {
        const opt = document.createElement('option');
        opt.value = gender;
        opt.textContent = gender;
        genderSelect.appendChild(opt);
    });
    genderSelect.value = selectedGender;
}

genderSelect.addEventListener('change', () => {
    selectedGender = genderSelect.value;
    fetchRecommendations();
});

function renderTagBar(colors, styles, products) {
    tagBar.innerHTML = '';
    // Render color tags
    colors.forEach(color => {
        const product = products.find(p => p.color === color);
        const img = product ? product.image : '';
        const tag = document.createElement('div');
        tag.className = 'tag-item cursor-pointer rounded-lg px-4 py-2 flex items-center bg-green-200 hover:bg-green-300';
        tag.onclick = () => { selectedTags.color = color; fetchRecommendations(); highlightSelectedTags(); };
        tag.innerHTML = `<img src="/${img}" class="w-8 h-8 rounded-full mr-2" alt="${color}" onerror="this.style.display='none'"><span>${color}</span>`;
        tag.dataset.type = 'color';
        tag.dataset.value = color;
        tagBar.appendChild(tag);
    });
    // Render style tags
    styles.forEach(style => {
        const product = products.find(p => p.style === style);
        const img = product ? product.image : '';
        const tag = document.createElement('div');
        tag.className = 'tag-item cursor-pointer rounded-lg px-4 py-2 flex items-center bg-blue-200 hover:bg-blue-300';
        tag.onclick = () => { selectedTags.style = style; fetchRecommendations(); highlightSelectedTags(); };
        tag.innerHTML = `<img src="/${img}" class="w-8 h-8 rounded-full mr-2" alt="${style}" onerror="this.style.display='none'"><span>${style}</span>`;
        tag.dataset.type = 'style';
        tag.dataset.value = style;
        tagBar.appendChild(tag);
    });
}

function highlightSelectedTags() {
    document.querySelectorAll('.tag-item').forEach(tag => {
        tag.classList.remove('ring-2', 'ring-primary-dark');
        if ((tag.dataset.type === 'color' && tag.dataset.value === selectedTags.color) ||
            (tag.dataset.type === 'style' && tag.dataset.value === selectedTags.style)) {
            tag.classList.add('ring-2', 'ring-primary-dark');
        }
    });
}

async function fetchRecommendations() {
    // Show loader, fade recommendations
    document.getElementById('recommendations-loader').classList.remove('hidden');
    recommendationsDiv.classList.add('opacity-50', 'pointer-events-none');

    let url = '/api/products-for-ml?';
    if (selectedGender) url += `gender=${encodeURIComponent(selectedGender)}&`;
    if (selectedTags.style) url += `styles=${encodeURIComponent(selectedTags.style)}&`;
    if (selectedTags.color) url += `colors=${encodeURIComponent(selectedTags.color)}&`;
    const res = await fetch(url);
    const data = await res.json();
    renderRecommendations(data.products || []);

    // Hide loader, restore recommendations
    document.getElementById('recommendations-loader').classList.add('hidden');
    recommendationsDiv.classList.remove('opacity-50', 'pointer-events-none');
}

// Event listeners
refreshPersonalizedBtn.addEventListener('click', loadPersonalizedRecommendations);

document.addEventListener('DOMContentLoaded', function() {
    loadPersonalizedRecommendations();
    fetchTagsAndProducts();
});
</script>
<style>
@keyframes slideUp { from { opacity: 0; transform: translateY(40px);} to { opacity: 1; transform: none; } }
.slide-up { animation: slideUp 0.7s cubic-bezier(.4,0,.2,1) both; }
</style>