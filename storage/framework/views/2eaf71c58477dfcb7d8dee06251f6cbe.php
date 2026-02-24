

<?php $__env->startSection('title', $product->title); ?>

<?php $__env->startSection('content'); ?>
<nav class="breadcrumb">
    <div class="breadcrumb-container">
        <a href="<?php echo e(route('home')); ?>">Home</a>
        <span class="breadcrumb-separator">/</span>
        <a href="<?php echo e(route('shop.index')); ?>">Shop</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current"><?php echo e($product->title); ?></span>
    </div>
</nav>

<section class="product-detail">
    <div class="product-detail-container">
        <div class="product-gallery">
            <div class="product-main-image">
                <?php if($product->images->count() > 0): ?>
                    <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" alt="<?php echo e($product->title); ?>" id="mainImage">
                <?php else: ?>
                    <img src="<?php echo e(asset('imgs/book-sarahs-perfect-gift.jpg')); ?>" alt="<?php echo e($product->title); ?>" id="mainImage">
                <?php endif; ?>
                <?php if($product->is_featured): ?>
                    <span class="product-badge">Popular</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-detail-info">
            <span class="product-category-tag"><?php echo e($product->categories->first()?->name ?? "Children's Book"); ?></span>
            <h1 class="product-detail-title"><?php echo e($product->title); ?></h1>

            <div class="product-rating">
                <div class="stars">★★★★★</div>
                <span class="rating-count">(<?php echo e($product->approvedReviews()->count()); ?> reviews)</span>
            </div>

            <p class="product-detail-price"><?php echo e($product->getPriceFormatted()); ?></p>

            <div class="product-detail-description">
                <p><?php echo e($product->short_description ?: 'An uplifting children\'s storybook that celebrates confidence, family love and values.'); ?></p>
                <?php if($product->long_description): ?>
                    <p><?php echo e(\Illuminate\Support\Str::limit(strip_tags($product->long_description), 280)); ?></p>
                <?php endif; ?>
            </div>

            <div class="product-stock <?php echo e($product->hasStock() ? 'in-stock' : 'out-of-stock'); ?>">
                <?php echo e($product->hasStock() ? 'In Stock' : 'Out of Stock'); ?>

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
                    <span class="meta-value"><?php echo e($product->sku); ?></span>
                </div>
                <?php if($product->categories->count() > 0): ?>
                    <div class="meta-item">
                        <span class="meta-label">Categories:</span>
                        <span class="meta-value"><?php echo e($product->categories->pluck('name')->join(', ')); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="product-tabs-section">
    <div class="product-tabs-container">
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="description" type="button">Description</button>
            <button class="tab-btn" data-tab="specifications" type="button">Specifications</button>
            <button class="tab-btn" data-tab="reviews" type="button">Reviews (<?php echo e($product->approvedReviews()->count()); ?>)</button>
        </div>

        <div class="tabs-content">
            <div class="tab-panel active" id="description">
                <h3>About This Book</h3>
                <p><?php echo e($product->long_description ?: $product->short_description ?: 'A meaningful and inspiring story crafted for children and families.'); ?></p>
            </div>

            <div class="tab-panel" id="specifications">
                <h3>Book Details</h3>
                <table class="specs-table">
                    <tr><th>Format</th><td><?php echo e(ucfirst($product->type)); ?></td></tr>
                    <tr><th>SKU</th><td><?php echo e($product->sku); ?></td></tr>
                    <tr><th>Availability</th><td><?php echo e($product->hasStock() ? 'In Stock' : 'Out of Stock'); ?></td></tr>
                </table>
            </div>

            <div class="tab-panel" id="reviews">
                <h3>Customer Reviews</h3>
                <?php $__empty_1 = true; $__currentLoopData = $product->approvedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="review-item">
                        <strong><?php echo e($review->reviewer_name); ?></strong>
                        <p><?php echo e($review->comment); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No reviews yet for this book.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if($relatedProducts->isNotEmpty()): ?>
    <section class="related-products">
        <div class="related-products-container">
            <h2 class="section-title">You May Also Like</h2>
            <div class="related-grid">
                <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-card">
                        <a href="<?php echo e(route('product.show', $relatedProduct->slug)); ?>" class="product-link">
                            <div class="product-image">
                                <?php if($relatedProduct->primaryImage): ?>
                                    <img src="<?php echo e(asset('storage/' . $relatedProduct->primaryImage->image_path)); ?>" alt="<?php echo e($relatedProduct->title); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('imgs/book-what-if.jpg')); ?>" alt="<?php echo e($relatedProduct->title); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo e($relatedProduct->title); ?></h3>
                                <p class="product-price"><?php echo e($relatedProduct->getPriceFormatted()); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<script>
    const qtyInput = document.getElementById('qtyInput');
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus = document.getElementById('qtyPlus');
    const productId = <?php echo e($product->id); ?>;

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/solob/dev/tokesi/Tokesi/resources/views/shops/product-details.blade.php ENDPATH**/ ?>