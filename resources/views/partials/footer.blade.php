<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-columns">
            <div class="footer-column">
                <h4 class="footer-heading">Navigate</h4>
                <a href="{{ route('home') }}" class="footer-link">Home</a>
                <a href="{{ route('shop.index') }}" class="footer-link">Shop</a>
                <a href="{{ route('about') }}" class="footer-link">About</a>
                <a href="{{ route('blog.index') }}" class="footer-link">Blog</a>
                <a href="{{ route('contact') }}" class="footer-link">Contact</a>
            </div>
            <div class="footer-column">
                <h4 class="footer-heading">Connect</h4>
                <a href="{{ route('contact') }}" class="footer-link">Contact Us</a>
                <a href="{{ route('blog.index') }}" class="footer-link">Blog & Events</a>
            </div>
            <div class="footer-column footer-newsletter">
                <h4 class="footer-heading">Newsletter</h4>
                <p class="footer-text">Sign up for updates and new releases</p>
                <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email" class="newsletter-input" required>
                    <button type="submit" class="newsletter-btn" aria-label="Subscribe">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
           <p>&copy; 2026 Tokesi Akinola. All rights reserved.</p>
        </div>
    </div>
</footer>