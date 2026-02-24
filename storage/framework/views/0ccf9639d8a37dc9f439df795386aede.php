
<header class="header">
    <div class="header-container">
        <a href="<?php echo e(route('home')); ?>" class="logo">Tokesi Akinola</a>
        <nav class="nav">
            <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Home</a>
            <a href="<?php echo e(route('shop.index')); ?>" class="nav-link <?php echo e(request()->routeIs('shop.*') ? 'active' : ''); ?>">Shop</a>
            <a href="<?php echo e(route('about')); ?>" class="nav-link <?php echo e(request()->routeIs('about') ? 'active' : ''); ?>">About</a>
            <a href="<?php echo e(route('blog.index')); ?>" class="nav-link <?php echo e(request()->routeIs('blog.*') ? 'active' : ''); ?>">Blog</a>
            <a href="<?php echo e(route('contact')); ?>" class="nav-link <?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>">Contact</a>
            <button id="cartBtn" class="header-cart-btn" aria-label="Shopping cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 2L7 6H3C2.4 6 2 6.4 2 7V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V7C22 6.4 21.6 6 21 6H17L15 2H9Z"/>
                    <circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/>
                </svg>
                <span id="cartBadge" class="cart-badge" style="display:none;">0</span>
            </button>
        </nav>
        
        <button class="header-mobile-toggle" id="menuBtn" aria-label="Toggle menu">
            <svg class="menu-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg class="close-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <div class="header-mobile-nav" id="mobileMenu" style="display:none;">
        <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Home</a>
        <a href="<?php echo e(route('shop.index')); ?>" class="nav-link <?php echo e(request()->routeIs('shop.*') ? 'active' : ''); ?>">Shop</a>
        <a href="<?php echo e(route('about')); ?>" class="nav-link <?php echo e(request()->routeIs('about') ? 'active' : ''); ?>">About</a>
        <a href="<?php echo e(route('blog.index')); ?>" class="nav-link <?php echo e(request()->routeIs('blog.*') ? 'active' : ''); ?>">Blog</a>
        <a href="<?php echo e(route('contact')); ?>" class="nav-link <?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>">Contact</a>
    </div>
</header>


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
        <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary" style="display:block;text-align:center;margin-top:1rem;">Proceed to Checkout</a>
    </div>
</div><?php /**PATH /Users/solob/dev/tokesi/resources/views/partials/header.blade.php ENDPATH**/ ?>