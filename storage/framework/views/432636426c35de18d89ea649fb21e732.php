<?php $__env->startSection('title', 'Contact Tokesi Akinola'); ?>
<?php $__env->startSection('meta_description', 'Get in touch with children\'s author Tokesi Akinola in Wigan, Greater Manchester for book inquiries, events, school visits, or collaborations.'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-hero">
    <div class="page-hero-container">
        <h1 class="page-hero-title">Get In Touch</h1>
        <p class="page-hero-subtitle">I'd love to hear from readers, parents, and educators</p>
    </div>
    <div class="page-hero-decoration"></div>
</section>

<section class="contact-section">
    <div class="contact-container">
        <div class="contact-info">
            <h2 class="section-title-left">Let's Connect</h2>
            <p class="contact-intro">Whether you're interested in a school visit, book reading, educational collaboration, or simply want to share your thoughts about my books, I'm here to listen.</p>

            <div class="contact-details">
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Phone</h4>
                        <p>+44 7498 624891</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Email</h4>
                        <p>author@tokesiakinola.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Location</h4>
                        <p>Manchester, United Kingdom</p>
                    </div>
                </div>
            </div>

            <div class="contact-services">
                <h3>Available For</h3>
                <ul class="services-list">
                    <li>School visits and assemblies</li>
                    <li>Library reading sessions</li>
                    <li>Book signings and events</li>
                    <li>Educational workshops</li>
                    <li>Storytelling clubs</li>
                    <li>Media interviews</li>
                </ul>
            </div>
        </div>

        <div class="contact-form-wrapper">
            <form class="contact-form" id="contactForm" action="<?php echo e(route('contact.submit')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <h3 class="form-title">Send a Message</h3>

                <div class="form-group">
                    <label for="name">Your Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email address">
                </div>

                <div class="form-group">
                    <label for="subject">Subject <span class="required">*</span></label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="school-visit">School Visit Enquiry</option>
                        <option value="book-reading">Book Reading Request</option>
                        <option value="collaboration">Collaboration Opportunity</option>
                        <option value="media">Media / Interview</option>
                        <option value="feedback">Book Feedback</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" rows="6" required minlength="20" maxlength="500" placeholder="Write your message here (20-500 characters)"></textarea>
                    <span class="char-count"><span id="charCount">0</span>/500</span>
                </div>

                <button type="submit" class="btn btn-primary btn-full">SEND MESSAGE</button>

                <div class="form-message" id="formMessage"></div>
            </form>
        </div>
    </div>
</section>

<section class="map-section">
    <div class="map-placeholder">
        <div class="map-content">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            <p>Manchester, United Kingdom</p>
        </div>
    </div>
</section>

<script>
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    if (messageField && charCount) {
        messageField.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/solob/dev/tokesi/resources/views/contact.blade.php ENDPATH**/ ?>