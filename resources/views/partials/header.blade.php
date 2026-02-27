{{-- Reference design header: cream background, logo left, nav right --}}
<header class="header">
    <div class="header-container">
        <a href="{{ route('home') }}" class="logo">Tokesi Akinola</a>
        <nav class="nav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}">Shop</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a>
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            <a href="{{ route('cart.index') }}" class="header-cart-btn" aria-label="Shopping cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4.00488 16V4H2.00488V2H5.00488C5.55717 2 6.00488 2.44772 6.00488 3V15H18.4433L20.4433 7H8.00488V5H21.7241C22.2764 5 22.7241 5.44772 22.7241 6C22.7241 6.08176 22.7141 6.16322 22.6942 6.24254L20.1942 16.2425C20.083 16.6877 19.683 17 19.2241 17H5.00488C4.4526 17 4.00488 16.5523 4.00488 16ZM6.00488 23C4.90031 23 4.00488 22.1046 4.00488 21C4.00488 19.8954 4.90031 19 6.00488 19C7.10945 19 8.00488 19.8954 8.00488 21C8.00488 22.1046 7.10945 23 6.00488 23ZM18.0049 23C16.9003 23 16.0049 22.1046 16.0049 21C16.0049 19.8954 16.9003 19 18.0049 19C19.1094 19 20.0049 19.8954 20.0049 21C20.0049 22.1046 19.1094 23 18.0049 23Z"/>
                </svg>
                <span id="cartBadge" class="cart-badge" style="display:none;">0</span>
            </a>
        </nav>
        {{-- Mobile menu toggle --}}
        <button class="header-mobile-toggle" id="menuBtn" aria-label="Toggle menu">
            <svg class="menu-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg class="close-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    {{-- Mobile nav --}}
    <div class="header-mobile-nav" id="mobileMenu" style="display:none;">
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}">Shop</a>
        <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a>
        <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </div>
</header>

{{-- Search modal (Laravel feature, overlaid on top of reference design) --}}
<div class="lk-search-overlay" id="searchModal" style="display:none;">
    <div class="lk-search-box">
        <div class="lk-search-input-row">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" placeholder="Search products..." id="searchInput" />
            <button onclick="closeSearchModal()" style="background:none;border:none;cursor:pointer;font-size:1.25rem;color:#666;">&times;</button>
        </div>
        <div id="searchResults"></div>
    </div>
</div>

{{-- Cart panel (Laravel feature, overlaid on top of reference design) --}}
<div class="lk-cart-overlay" id="cartOverlay" style="display:none;"></div>
<div class="lk-cart-panel" id="cartPanel">
    <div class="lk-cart-header">
        <h2>Shopping Cart</h2>
        <button id="cartClose" aria-label="Close cart">&times;</button>
    </div>
    <div class="lk-cart-items" id="cartItems"></div>
    <div class="lk-cart-summary" id="cartSummary" style="display:none;">
        <div class="lk-cart-total">
            <span>Total</span>
            <span id="cartTotalPrice">£0.00</span>
        </div>
        <a href="{{ route('checkout') }}" class="btn btn-primary" style="display:block;text-align:center;margin-top:1rem;">Proceed to Checkout</a>
    </div>
</div>