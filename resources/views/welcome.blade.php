<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DURABAG - Bags You Can Trust</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #0a0d13;
            --card-bg: #181c23;
            --accent:rgb(38, 144, 231);
            --text-main: #fff;
            --text-secondary: #b0b3b8;
        }
        html, body {
            height: 100%;
            min-height: 100%;
        }
        body {
            
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background: rgba(13, 0, 0, 0.55);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .brand-yellow { color: var(--accent); }
        .btn-accent {
            background: var(--accent);
            color: #111;
            border: none;
            font-weight: 600;
        }
        .btn-accent:hover {
            background: #fff;
            color: var(--accent);
        }
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            letter-spacing: -2px;
        }
        .hero-tagline {
            font-size: 1.5rem;
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 2rem;
        }
        .hero-section {
            padding: 80px 0 40px 0;
            background: linear-gradient(135deg,rgb(79, 79, 79) 0%, #000 100%);
        }
        .product-card {
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
        }
        .product-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0,0,0,0.28);
        }
        .product-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #222;
        }
        .product-title {
            color: var(--accent);
            font-weight: 700;
            font-size: 1.2rem;
        }
        .product-desc {
            color: var(--text-secondary);
            font-size: 1rem;
        }
        .product-card .p-3 {
            background: transparent !important;
        }
        .main-content {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
        @media (max-width: 767px) {
            .hero-title { font-size: 2.5rem; }
            .hero-section { padding: 40px 0 20px 0; }
        }
        #products, footer {
            background: white !important;
            color: #111;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background: linear-gradient(20deg, #0a2342 0%, #111 100%); padding: 0.5rem 0; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#" style="color: #fff; font-weight: bold; font-size: 2rem;">
                <img src="/images/logo.png" alt="Logo" style="height: 32px; margin-right: 8px;">
                Durabag
            </a>
            <div class="d-flex align-items-center gap-3">
                
                <!-- Globe -->
                <a href="#" class="text-white" style="font-size: 1.3rem;"><i class="fas fa-globe"></i></a>
                <!-- Cart -->
                <a href="#" class="text-white" style="font-size: 1.3rem;"><i class="fas fa-shopping-cart"></i></a>
                <!-- User -->
                <a href="#" class="text-white" style="font-size: 1.3rem;"><i class="fas fa-user"></i></a>
                <!-- Create Account Button -->
                <a href="{{ route('register') }}" class="btn" style="background:rgb(0, 187, 255); color: #fff; font-weight: bold; font-size: 1.1rem; border-radius: 2rem; padding: 0.5rem 1.5rem;">
                    Create account
                </a>
                <a href="{{ route('login') }}" class="btn" style="background:rgb(0, 187, 255); color: #fff; font-weight: bold; font-size: 1.1rem; border-radius: 2rem; padding: 0.5rem 1.5rem;">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="position:relative; background: none;">
        <div style="position:absolute;top:0;left:0;width:100%;height:100%;background: linear-gradient(135deg,rgba(30,30,30,0.7) 0%,rgba(0,0,0,0.7) 100%), url('/images/pixel1.jpg') center center/cover no-repeat; z-index:0;"></div>
        <div class="container" style="position:relative;z-index:1;">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <div class="hero-title">
                        <span class="brand-yellow">DURA</span>BAG
                    </div>
                    <div class="hero-tagline">BAGS YOU CAN TRUST</div>
                    <h2 class="fw-bold mb-4">Premium Quality Bags for Every Journey</h2>
                    <div class="mb-4" style="color:var(--text-secondary);">
                        Discover our collection of meticulously crafted bags designed for durability, style, and functionality. From business travels to outdoor adventures, Durabag has you covered.
                    </div>
                    <div style="display:inline-block; background:rgba(255,255,255,0.12); border-radius:2rem; box-shadow:0 2px 12px rgba(0,0,0,0.10); padding:0.5rem 1.5rem;">
                        <a href="#products" class="btn btn-accent btn-lg px-4 d-flex align-items-center gap-2" style="font-weight:600; border-radius:2rem;">
                            Discover
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path fill="currentColor" d="M13.293 6.293a1 1 0 0 1 1.414 0l5 5a1 1 0 0 1 0 1.414l-5 5a1 1 0 1 1-1.414-1.414L16.586 13H5a1 1 0 1 1 0-2h11.586l-3.293-3.293a1 1 0 0 1 0-1.414z"/></svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="product-card d-flex flex-row align-items-center">
                                <img src="/images/bag4.avif" class="product-img" alt="Professional Backpack">
                                <div class="p-3">
                                    <div class="product-title">Professional Backpack</div>
                                    <div class="product-desc">Built for the modern professional</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="product-card" style="box-shadow: 0 4px 24px rgba(210, 207, 207, 0.18);">
                                <img src="/images/bag5.webp" class="product-img" alt="Leather Satchel">
                                <div class="p-3">
                                    <div class="product-title">Leather Satchel</div>
                                    <div class="product-desc">Professional elegance</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="product-card" style="box-shadow: 0 4px 24px rgba(210, 207, 207, 0.18);">
                                <img src="/images/blue.avif.jpg" class="product-img" alt="Leather Duffel">
                                <div class="p-3">
                                    <div class="product-title">Leather Duffel</div>
                                    <div class="product-desc">Travel in style</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Cards Section -->
    <section id="products" class="py-5">
        <div class="container" >
            <h3 class="mb-4 fw-bold brand-yellow">Featured Products</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="/images/pixel4.jpg" class="product-img" alt="Professional Backpack">
                        <div class="p-3">
                            <div class="product-title">Kids' Backpack</div>
                            <div class="product-desc">Built for the modern professional</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="/images/pixel2.jpeg" class="product-img" alt="Leather Satchel">
                        <div class="p-3">
                            <div class="product-title">Leather Satchel</div>
                            <div class="product-desc">Professional elegance</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="/images/pixel6.webp" class="product-img" alt="Leather Duffel">
                        <div class="p-3">
                            <div class="product-title">Leather Duffel</div>
                            <div class="product-desc">Travel in style</div>
                        </div>
                    </div>
                </div>
                <!-- Add more product cards as needed -->
            </div>
        </div>
    </section>
    <!-- Trade Confidence Section -->
    <section class="py-5" style="background: url('/images/main.jpg') center center/cover no-repeat; position: relative; color: #fff;">
        <div style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(60,40,20,0.55);z-index:1;"></div>
        <div class="container" style="position:relative;z-index:2;">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold display-5 mb-3">Trade with confidence from<br>production quality to purchase protection</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 rounded-4 h-100 d-flex flex-column justify-content-between" style="background: rgba(0,0,0,0.25); min-height: 260px;">
                        <div>
                            <div class="mb-3" style="font-size:1.2rem; font-weight:600;">Ensure production quality with</div>
                            <div class="mb-2" style="font-size:2rem; font-weight:800;">
                                <span style="color:#1e90ff;">V</span><span style="color:#fff;">erified Supplier/</span>
                                <span style="color:#1e90ff;">R</span><span style="color:#fff;">etailer</span>
                            </div>
                            <div class="mb-3" style="font-size:1rem; color:#f3f3f3;">
                                Connect with a variety of suppliers with third-party-verified credentials and capabilities. Look for the "Verified" logo to begin sourcing with experienced suppliers your business could rely on.
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-4 h-100 d-flex flex-column justify-content-between" style="background: rgba(0,0,0,0.25); min-height: 260px;">
                        <div>
                            <div class="mb-3" style="font-size:1.2rem; font-weight:600;">Protect your purchase with</div>
                            <div class="mb-2" style="font-size:2rem; font-weight:800;">
                                <span style="color:#ffc107; font-size:2.2rem; vertical-align:middle;">&#128176;</span>
                                <span style="color:#fff;">Trade Assurance</span>
                            </div>
                            <div class="mb-3" style="font-size:1rem; color:#f3f3f3;">
                                Source confidently with access to secure payment options, protection against product issues, and mediation support for any purchase-related concerns when you order and pay on Durabag.com.
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="text-center mt-5 py-4">
        &copy; {{ date('Y') }} DURABAG. All rights reserved.
    </footer>
    
</body>
</html>