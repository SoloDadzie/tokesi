@extends('layouts.app')

@section('title', 'Shop Children’s Books by Tokesi Akinola | Buy Kids’ Books Online')

@section('meta_description', 'Buy children’s books by Tokesi Akinola, Shop inspiring kids’ stories crafted to spark creativity and imagination.')

@section('content')
<section class="page-hero">
    <div class="page-hero-container">
        <h1 class="page-hero-title">Shop</h1>
        <p class="page-hero-subtitle">Discover faith-based children's books that inspire and nurture young hearts</p>
    </div>
    <div class="page-hero-decoration"></div>
</section>

<section class="shop-section">
    <div class="shop-container">
        <aside class="shop-sidebar">
            <div class="filter-group">
                <h3 class="filter-title">Categories</h3>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" checked>
                        <span class="checkmark"></span>
                        All Books
                    </label>
                    @foreach($categories as $category)
                        <label class="filter-checkbox">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="filter-group">
                <h3 class="filter-title">Price Range</h3>
                <div class="price-range">
                    <input type="range" min="0" max="10" value="10" class="price-slider" id="priceRange">
                    <div class="price-labels">
                        <span>£0</span>
                        <span id="priceValue">£10.00</span>
                    </div>
                </div>
            </div>

            <div class="filter-group">
                <h3 class="filter-title">Sort By</h3>
                <select class="filter-select">
                    <option value="featured">Featured</option>
                    <option value="newest">Newest</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                </select>
            </div>
        </aside>

        <div class="products-section">
            <div class="products-header">
                <p class="products-count">Showing {{ $products->count() }} of {{ $products->total() }} books</p>
            </div>

            <div class="products-grid">
                @forelse($products as $product)
                    <a href="{{ route('product.show', $product->slug) }}" class="product-card">
                        <div class="product-image">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->title }}">
                            @else
                                <img src="{{ asset('imgs/book-sarahs-perfect-gift.jpg') }}" alt="{{ $product->title }}">
                            @endif
                            @if($product->is_featured)
                                <span class="product-badge">Popular</span>
                            @elseif($product->is_new_arrival)
                                <span class="product-badge">New</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">{{ $product->title }}</h3>
                            <p class="product-description">{{ $product->short_description ? \Illuminate\Support\Str::limit(strip_tags($product->short_description), 110) : 'Discover this inspiring story crafted for young readers.' }}</p>
                            <div class="product-footer">
                                <span class="product-price">{{ $product->getPriceFormatted() }}</span>
                                <button class="btn btn-sm" data-original-content="Add to Cart" onclick="event.preventDefault(); event.stopPropagation(); addToCart('{{ $product->id }}', 1, null, this)">Add to Cart</button>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="product-card">
                        <div class="product-info">
                            <h3 class="product-title">No matching books found</h3>
                            <p class="product-description">Try adjusting your filters or search terms.</p>
                            <div class="product-footer">
                                <a href="{{ route('shop.index') }}" class="btn btn-outline btn-sm">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div style="margin-top: 2rem;">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

<section class="amazon-banner">
    <div class="amazon-container">
        <div class="amazon-content">
            <h2>Also Available on Amazon</h2>
            <p>Prefer to shop on Amazon? All our books are available for purchase with fast delivery options.</p>
            <a href="https://www.amazon.co.uk" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">Shop on Amazon</a>
        </div>
    </div>
</section>

<section class="why-buy">
    <div class="why-buy-container">
        <h2 class="section-title">Why Choose Our Books?</h2>
        <div class="why-buy-grid">
            <div class="why-buy-card">
                <div class="why-buy-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3>Faith-Based Values</h3>
                <p>Stories that nurture spiritual growth and moral development in children.</p>
            </div>
            <div class="why-buy-card">
                <div class="why-buy-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                </div>
                <h3>Award-Winning Author</h3>
                <p>Written by Tokesi Akinola, a recognised children's author with a passion for storytelling.</p>
            </div>
            <div class="why-buy-card">
                <div class="why-buy-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <h3>Quality Illustrations</h3>
                <p>Beautifully illustrated pages that capture children's imagination and attention.</p>
            </div>
            <div class="why-buy-card">
                <div class="why-buy-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="3" width="15" height="13"/>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/>
                        <circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                </div>
                <h3>Fast Delivery</h3>
                <p>Quick and reliable shipping to get books into young readers' hands promptly.</p>
            </div>
        </div>
    </div>
</section>

<script>
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');

    if (priceRange && priceValue) {
        priceRange.addEventListener('input', function () {
            const formatted = Number(this.value).toFixed(2);
            priceValue.textContent = `£${formatted}`;
        });
    }
</script>
@endsection