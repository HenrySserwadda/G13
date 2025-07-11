<div class="container mx-auto py-4">
    <h1 class="text-2xl font-bold mb-4">Recommended Products</h1>

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
    </style>
@endpush

<script>
const tagBar = document.getElementById('tag-bar');
const recommendationsDiv = document.getElementById('recommendations');
const genderSelect = document.getElementById('gender-select');
let selectedTags = { color: null, style: null };
let allTags = { colors: [], styles: [], genders: [] };
let selectedGender = '';
let allProducts = [];

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
        tag.innerHTML = `<img src="/${img}" class="w-8 h-8 rounded-full mr-2" alt="${color}"><span>${color}</span>`;
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
        tag.innerHTML = `<img src="/${img}" class="w-8 h-8 rounded-full mr-2" alt="${style}"><span>${style}</span>`;
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

function renderRecommendations(products) {
    recommendationsDiv.innerHTML = '';
    if (!products.length) {
        recommendationsDiv.innerHTML = '<div class="col-span-full text-center py-12"><div class="text-gray-500 text-xl"><i class="fas fa-box-open text-5xl mb-4"></i><p class="text-2xl font-light">No recommendations found</p></div></div>';
        return;
    }
    products.forEach(product => {
        const hasImage = product.image && product.image !== 'images/dataset/none';
        let card = `<div class=\"product-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl\">`;
        card += `<div class=\"product-image-container\">`;
        if (hasImage) {
            card += `<img src=\"/${product.image}\" alt=\"${product.name}\" class=\"product-image\" loading=\"lazy\">`;
        } else {
            card += `<div class=\"no-image-placeholder\"><i class=\"fas fa-camera text-5xl text-gray-400\"></i></div>`;
        }
        card += `</div>`;
        card += `<div class=\"p-5\">`;
        card += `<div class=\"flex justify-between items-start\">`;
        card += `<h2 class=\"text-xl font-bold text-gray-800 mb-2\">${product.name}</h2>`;
        card += `<div class=\"text-lg font-bold text-blue-600\">UGX ${Number(product.price).toLocaleString()}</div>`;
        card += `</div>`;
        card += `<p class=\"text-gray-600 mb-4 line-clamp-2\">${product.description}</p>`;
        card += `<div class=\"flex space-x-3\">`;
        card += `<a href=\"/products?name=${encodeURIComponent(product.name)}\" class=\"bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-300 flex-1 justify-center\"><i class=\"fas fa-eye mr-2\"></i> View</a>`;
        // Add to Cart button (real form, only for wholesaler/retailer)
        if (window.LaravelUserCategory === 'wholesaler' || window.LaravelUserCategory === 'retailer') {
            card += `<form action=\"${window.LaravelCartAddRoute.replace('PRODUCT_ID', product.id)}\" method=\"POST\" class=\"flex-1\">`;
            card += `<input type=\"hidden\" name=\"_token\" value=\"${window.LaravelCsrfToken}\">`;
            card += `<button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full flex items-center justify-center transition-colors duration-300\"><i class=\"fas fa-cart-plus mr-2\"></i> Add to Cart</button>`;
            card += `</form>`;
        }
        card += `</div>`;
        card += `</div>`;
        card += `</div>`;
        recommendationsDiv.innerHTML += card;
    });
}

document.addEventListener('DOMContentLoaded', fetchTagsAndProducts);
</script>