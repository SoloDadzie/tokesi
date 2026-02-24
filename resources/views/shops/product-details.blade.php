@extends('layouts.app')

@section('title', $product->title)

@section('content')
<nav class="breadcrumb">
    <div class="breadcrumb-container">
        <a href="{{ route('home') }}">Home</a>
        <span class="breadcrumb-separator">/</span>
        <a href="{{ route('shop.index') }}">Shop</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">{{ $product->title }}</span>
    </div>
</nav>

<section class="product-detail">
    <div class="product-detail-container">
        <div class="product-gallery">
            <div class="product-main-image">
                @if($product->images->count() > 0)
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}" id="mainImage">
                @else
                    <img src="{{ asset('imgs/book-sarahs-perfect-gift.jpg') }}" alt="{{ $product->title }}" id="mainImage">
                @endif
                @if($product->is_featured)
                    <span class="product-badge">Popular</span>
                @endif
            </div>
        </div>

        <div class="product-detail-info">
            <span class="product-category-tag">{{ $product->categories->first()?->name ?? "Children's Book" }}</span>
            <h1 class="product-detail-title">{{ $product->title }}</h1>

            <div class="product-rating">
                <div class="stars">★★★★★</div>
                <span class="rating-count">({{ $product->approvedReviews()->count() }} reviews)</span>
            </div>

            <p class="product-detail-price">{{ $product->getPriceFormatted() }}</p>

            <div class="product-detail-description">
                <p>{!! $product->short_description ?: 'An uplifting children\'s storybook that celebrates confidence, family love and values.' !!}</p>
                @if($product->long_description)
                    <p>{!! \Illuminate\Support\Str::limit(strip_tags($product->long_description), 280) !!}</p>
                @endif
            </div>

            <div class="product-stock {{ $product->hasStock() ? 'in-stock' : 'out-of-stock' }}">
                {{ $product->hasStock() ? 'In Stock' : 'Out of Stock' }}
            </div>

            <div class="purchase-controls">
                <div class="quantity-selector">
                    <button class="qty-btn" id="qtyMinus" type="button">-</button>
                    <input type="number" value="1" min="1" max="10" class="qty-input" id="qtyInput">
                    <button class="qty-btn" id="qtyPlus" type="button">+</button>
                </div>
                <button type="button" class="btn btn-primary add-to-cart-btn" onclick="addToCartFromDetails()">Add to Cart</button>
            </div>

            <a href="https://www.amazon.co.uk" target="_blank" rel="noopener noreferrer" class="btn btn-secondary buy-amazon-btn">
                Buy on Amazon
            </a>

            <div class="product-meta">
                <div class="meta-item">
                    <span class="meta-label">SKU:</span>
                    <span class="meta-value">{{ $product->sku }}</span>
                </div>
                @if($product->categories->count() > 0)
                    <div class="meta-item">
                        <span class="meta-label">Categories:</span>
                        <span class="meta-value">{{ $product->categories->pluck('name')->join(', ') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="product-tabs-section">
    <div class="product-tabs-container">
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="description" type="button">Description</button>
            <button class="tab-btn" data-tab="specifications" type="button">Specifications</button>
            <button class="tab-btn" data-tab="reviews" type="button">Reviews ({{ $product->approvedReviews()->count() }})</button>
        </div>

        <div class="tabs-content">
            <div class="tab-panel active" id="description">
                <h3>About This Book</h3>
                <p>{!! $product->long_description ?: $product->short_description ?: 'A meaningful and inspiring story crafted for children and families.' !!}</p>
            </div>

            <div class="tab-panel" id="specifications">
                <h3>Book Details</h3>
                <table class="specs-table">
                    <tr><th>Format</th><td>{{ ucfirst($product->type) }}</td></tr>
                    <tr><th>SKU</th><td>{{ $product->sku }}</td></tr>
                    <tr><th>Availability</th><td>{{ $product->hasStock() ? 'In Stock' : 'Out of Stock' }}</td></tr>
                </table>
            </div>

            <div class="tab-panel" id="reviews">
                <h3>Customer Reviews</h3>
                @forelse($product->approvedReviews as $review)
                    <div class="review-item">
                        <strong>{{ $review->reviewer_name }}</strong>
                        <p>{{ $review->comment }}</p>
                    </div>
                @empty
                    <p>No reviews yet for this book.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

@if($relatedProducts->isNotEmpty())
    <section class="related-products">
        <div class="related-products-container">
            <h2 class="section-title">You May Also Like</h2>
            <div class="related-grid">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="product-card">
                        <a href="{{ route('product.show', $relatedProduct->slug) }}" class="product-link">
                            <div class="product-image">
                                @if($relatedProduct->primaryImage)
                                    <img src="{{ asset('storage/' . $relatedProduct->primaryImage->image_path) }}" alt="{{ $relatedProduct->title }}">
                                @else
                                    <img src="{{ asset('imgs/book-what-if.jpg') }}" alt="{{ $relatedProduct->title }}">
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">{{ $relatedProduct->title }}</h3>
                                <p class="product-price">{{ $relatedProduct->getPriceFormatted() }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<script>
    const qtyInput = document.getElementById('qtyInput');
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus = document.getElementById('qtyPlus');
    const productId = {{ $product->id }};

    if (qtyMinus && qtyInput) {
        qtyMinus.addEventListener('click', () => {
            if (Number(qtyInput.value) > 1) {
                qtyInput.value = String(Number(qtyInput.value) - 1);
            }
        });
    }

    if (qtyPlus && qtyInput) {
        qtyPlus.addEventListener('click', () => {
            if (Number(qtyInput.value) < 10) {
                qtyInput.value = String(Number(qtyInput.value) + 1);
            }
        });
    }

    function addToCartFromDetails() {
        const quantity = Number(qtyInput?.value || 1);
        addToCart(productId, quantity);
    }

    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabId = btn.getAttribute('data-tab');
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(tabId)?.classList.add('active');
        });
    });
</script>
@endsection