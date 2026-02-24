



<?php $__env->startSection('title', 'Tokesi Akinola Blog & Event| Tips, Stories & Children’s Reading Inspiration'); ?>
<?php $__env->startSection('meta_description', 'Explore kid-friendly reading tips, parenting insights, and writing updates from Wigan children’s author Tokesi Akinola.'); ?>


<?php $__env->startSection('content'); ?>
<section class="page-hero">
    <div class="page-hero-container">
        <h1 class="page-hero-title">Blogs & Events</h1>
        <p class="page-hero-subtitle">Here you can read insightful articles and view our recent events</p>
    </div>
    <div class="page-hero-decoration"></div>
</section>

<section class="blog-section">
    <div class="blog-container">
        <?php if($articles->count() > 0): ?>
            <div class="blog-grid">
                <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="blog-card">
                        <div class="blog-image">
                            <img src="<?php echo e($article->featured_image ? asset('storage/' . $article->featured_image) : asset('imgs/book-show-me-your-friends.jpg')); ?>" alt="<?php echo e($article->title); ?>">
                            <span class="blog-category"><?php echo e(ucfirst($article->type)); ?></span>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><?php echo e($article->published_at?->format('F j, Y')); ?></span>
                                <span class="blog-comments"><?php echo e($article->comments_count); ?> <?php echo e(Str::plural('Comment', $article->comments_count)); ?></span>
                            </div>
                            <h3 class="blog-title"><?php echo e($article->title); ?></h3>
                            <p class="blog-excerpt"><?php echo Str::limit(strip_tags($article->short_description ?? $article->content), 150); ?></p>
                            <a href="<?php echo e(route('blog.show', $article->slug)); ?>" class="btn btn-outline">Read More</a>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div style="margin-top: 2rem;">
                <?php echo e($articles->links()); ?>

            </div>
        <?php else: ?>
            <p class="blog-excerpt" style="text-align: center;">No articles found.</p>
        <?php endif; ?>
    </div>
</section>

<section class="events-section">
    <div class="events-container">
        <h2 class="section-title">Upcoming Events</h2>
        <div class="events-grid">
            <div class="event-card">
                <div class="event-date-badge"><span class="event-day">15</span><span class="event-month">Feb</span></div>
                <div class="event-content">
                    <h3 class="event-title">Book Reading & Signing</h3>
                    <p class="event-description">Join Tokesi for an afternoon of storytelling and book signing.</p>
                    <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline">Learn More</a>
                </div>
            </div>
            <div class="event-card">
                <div class="event-date-badge"><span class="event-day">22</span><span class="event-month">Mar</span></div>
                <div class="event-content">
                    <h3 class="event-title">School Visit Programme</h3>
                    <p class="event-description">Tokesi visits local schools to inspire the next generation of readers.</p>
                    <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline">Book a Visit</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/solob/dev/tokesi/resources/views/blogs/index.blade.php ENDPATH**/ ?>