<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SitemapController;

// Home/Products routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/shop/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/api/products/search', [ProductController::class, 'search'])->name('products.search');



// Product coupon validation
Route::post('/api/products/{id}/validate-coupon', [ProductController::class, 'validateCoupon'])->name('products.validate-coupon');
Route::post('/api/products/{id}/increment-coupon-usage', [ProductController::class, 'incrementCouponUsage'])->name('products.increment-coupon');

// Cart API routes
Route::prefix('api/cart')->group(function () {
    Route::get('/', [CartController::class, 'get'])->name('cart.get');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/', [CartController::class, 'clear'])->name('cart.clear');
    
    // Coupon routes
    Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
});


Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::get('/api/checkout/shipping-methods', [CheckoutController::class, 'getShippingMethods']);
Route::get('/api/checkout/cart-summary', [CheckoutController::class, 'getCartSummary']);

// Review routes
Route::post('/api/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/api/products/{id}/reviews', [ReviewController::class, 'getProductReviews'])->name('products.reviews');


Route::post('/api/checkout/create-payment-intent', [OrderController::class, 'createPaymentIntent']);
Route::post('/api/checkout/process-order', [OrderController::class, 'processOrder']);
Route::get('/order/confirmation/{orderNumber}', [OrderController::class, 'orderConfirmation'])->name('order.confirmation');

// Admin route for sending shipping emails
Route::post('/api/orders/{order}/send-shipping-notification', [OrderController::class, 'sendShippingNotification']);

// Blog routes
Route::get('/blogs', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comment', [BlogController::class, 'storeComment'])->name('blog.comment.store');

// Old route redirect (optional - for backwards compatibility)
Route::get('/blog-view', function () {
    return redirect()->route('blog.index');
});


// web.php - Update your contact route
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Newsletter subscription
Route::post('/newsletter/subscribe', function () {
    return back()->with('success', 'Thank you for subscribing to our newsletter!');
})->name('newsletter.subscribe');



/////SHOP PAGE
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/product/{id}/quick-view', [ShopController::class, 'quickView']);

// Add this route if you want to fetch testimonials via API/AJAX
Route::get('/api/testimonials', [TestimonialController::class, 'index']);


Route::get('/about', function () {
    return view('about');
})->name('about');


// Public order page (anyone can visit)
Route::get('/order', function () {
    return view('order');
})->name('order');

// Orders API / actions - ADD 'api/' prefix
Route::prefix('api/orders')->group(function () {
    Route::post('/track', [OrderController::class, 'trackOrder'])->name('orders.track');
    Route::post('/{orderNumber}/update-address', [OrderController::class, 'updateDeliveryAddress'])->name('orders.update-address');
    Route::post('/{orderNumber}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
});

// Sitemap routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::post('/admin/sitemap/clear-cache', [SitemapController::class, 'clearCache'])->name('sitemap.clear-cache');

Route::get('/{location}', [LocationController::class, 'show'])
    ->where('location', '^(?!blog$|contact$)[a-zA-Z]+$')
    ->name('location.show');



