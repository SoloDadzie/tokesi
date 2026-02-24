<?php $__env->startSection('title', 'Tokesi Akinola – Children’s Author in Manchester'); ?>

<?php $__env->startSection('meta_description', 'Children’s author Tokesi Akinola creates heartwarming stories that inspire imagination and make reading fun for kids.'); ?>

<?php $__env->startSection('content'); ?>
<section class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">Tokesi Akinola:<br>Crafting Stories<br>That Inspire Truth</h1>
            <p class="hero-subtitle">Award-winning children's author dedicated to nurturing young hearts through faith based storytelling.</p>
            <div class="hero-buttons">
                <a href="#books" class="btn btn-primary">EXPLORE THE BOOKS</a>
                <a href="<?php echo e(route('about')); ?>" class="btn btn-secondary">MEET TOKESI</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-image-frame">
                <img src="<?php echo e(asset('imgs/author-photo.jpg')); ?>" alt="Tokesi Akinola" class="author-photo">
            </div>
        </div>
    </div>
    <div class="hero-decorations">
        <div class="decoration decoration-top-right"></div>
        <div class="decoration decoration-bottom-left"></div>
    </div>
</section>

<section class="collection" id="books">
    <div class="collection-container">
        <h2 class="section-title">The Collection</h2>

        <div class="books-grid">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="book-card">
                    <div class="book-image">
                        <a href="<?php echo e(route('product.show', $product->slug)); ?>">
                            <?php if($product->primaryImage): ?>
                                <img src="<?php echo e(asset('storage/' . $product->primaryImage->image_path)); ?>" alt="<?php echo e($product->title); ?>">
                            <?php else: ?>
                                <img src="<?php echo e(asset('imgs/book-sarahs-perfect-gift.jpg')); ?>" alt="<?php echo e($product->title); ?>">
                            <?php endif; ?>
                        </a>
                    </div>
                    <h3 class="book-title"><?php echo e($product->title); ?></h3>
                    <button type="button" class="btn btn-outline" onclick="addToCart(<?php echo e($product->id); ?>, 1, null, this)">DISCOVER THE STORY</button>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="book-card">
                    <div class="book-image">
                        <img src="<?php echo e(asset('imgs/book-sarahs-perfect-gift.jpg')); ?>" alt="No products yet">
                    </div>
                    <h3 class="book-title">More stories coming soon</h3>
                    <a href="<?php echo e(route('shop.index')); ?>" class="btn btn-outline">DISCOVER THE STORY</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="about" id="about">
    <div class="about-container">
        <div class="about-image">
            <img src="<?php echo e(asset('imgs/author-photo.jpg')); ?>" alt="Tokesi Akinola">
        </div>
        <div class="about-content">
            <h2 class="section-title">About the Author</h2>
            <p class="about-text">Tokesi Akinola is a children's author who has dedicated her life to creating meaningful stories. Books and storytelling are her passion, driven by a desire to nurture young minds through engaging narratives that teach important values.</p>
            <p class="about-text">Inspiring children with stories that combine entertainment and life lessons, Tokesi creates books that parents and children can enjoy together.</p>
            <p class="about-highlight">Award-winning children's author dedicated to nurturing young hearts through faith-based storytelling.</p>
        </div>
    </div>
</section>

<section class="testimonial">
    <div class="testimonial-container">
        <h2 class="section-title">Testimonials</h2>
        <?php if($testimonials->count() > 0): ?>
            <div class="testimonial-slider" id="testimonialSlider">
                <?php if($testimonials->count() > 1): ?>
                    <button class="slider-btn slider-btn-prev" aria-label="Previous testimonial" onclick="changeTestimonial(-1)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6"/>
                        </svg>
                    </button>
                <?php endif; ?>
                
                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="testimonial-content" data-testimonial="<?php echo e($index); ?>" style="display: <?php echo e($index === 0 ? 'block' : 'none'); ?>">
                        <div class="quote-icon">"</div>
                        <blockquote class="testimonial-quote">
                            <?php echo e($testimonial->text); ?>

                        </blockquote>
                        <div class="testimonial-author">
                            <strong><?php echo e($testimonial->name); ?></strong>
                            <span><?php echo e($testimonial->location); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <?php if($testimonials->count() > 1): ?>
                    <button class="slider-btn slider-btn-next" aria-label="Next testimonial" onclick="changeTestimonial(1)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="testimonial-slider">
                <div class="testimonial-content">
                    <div class="quote-icon">"</div>
                    <blockquote class="testimonial-quote">
                        Meeting Tokesi Akinola as both a person and an author has been a joy, and her warmth and intentionality shine clearly through her books.
                    </blockquote>
                    <div class="testimonial-author">
                        <strong>Elsie Hayford</strong>
                        <span>Author / Children's Writer</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
let currentTestimonial = 0;
const testimonials = document.querySelectorAll('[data-testimonial]');

// Initialize first testimonial as active
if (testimonials.length > 0) {
    testimonials[0].classList.add('active');
    testimonials[0].style.display = 'block';
}

function changeTestimonial(direction) {
    if (testimonials.length === 0) return;
    
    const current = testimonials[currentTestimonial];
    
    // Add fade-out animation to current testimonial
    current.classList.remove('active');
    current.classList.add('fade-out');
    
    // Calculate next testimonial index
    currentTestimonial = (currentTestimonial + direction + testimonials.length) % testimonials.length;
    const next = testimonials[currentTestimonial];
    
    // Wait for fade-out animation, then switch testimonials
    setTimeout(() => {
        current.style.display = 'none';
        current.classList.remove('fade-out');
        
        next.style.display = 'block';
        // Small delay to trigger CSS transition
        setTimeout(() => {
            next.classList.add('active');
        }, 50);
    }, 600); // Match CSS transition duration
}

// Auto-rotate testimonials every 8 seconds
if (testimonials.length > 1) {
    setInterval(() => changeTestimonial(1), 8000);
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/solob/dev/tokesi/resources/views/welcome.blade.php ENDPATH**/ ?>