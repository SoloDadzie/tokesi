<script>
// Configuration
const STRIPE_KEY = '{{ $stripeKey }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Global state
let stripe, elements, paymentElement;
let currentStep = 1;
let shippingData = {};
let selectedShipping = null;
let cartData = {};
let selectedPaymentMethod = 'stripe';
let clientSecret = null;

// ==================== INITIALIZATION ====================

document.addEventListener('DOMContentLoaded', async () => {
    await loadCartSummary();
    initializePaymentOptions();
    updateStepperStyles();
    
    // Watch for country/state changes
    ['country', 'state'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('blur', () => {
                if (currentStep === 2) {
                    loadShippingMethods();
                }
            });
        }
    });
});

// ==================== UTILITY FUNCTIONS ====================

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#f59e0b'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ==================== CART & SUMMARY ====================

async function loadCartSummary() {
    try {
        const response = await fetch('/api/checkout/cart-summary', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        });
        const data = await response.json();
        
        if (data.success) {
            cartData = data;
            renderCartItems(data.items);
            updateSummaryTotals();
        } else {
            showNotification('Your cart is empty', 'error');
            setTimeout(() => window.location.href = '{{ route("home") }}', 2000);
        }
    } catch (error) {
        console.error('Failed to load cart:', error);
        showNotification('Failed to load cart data', 'error');
    }
}

function renderCartItems(items) {
    const container = document.getElementById('cartItemsList');
    if (!container || !items) return;
    
    container.innerHTML = items.map(item => `
        <div class="cart-summary-item">
            <img src="${item.image || '/images/placeholder-book.jpg'}" 
                 alt="${item.name}" 
                 class="cart-summary-image">
            <div class="cart-summary-details">
                <div class="cart-summary-name">${item.name}</div>
                <div class="cart-summary-qty">Qty: ${item.quantity} × £${item.price.toFixed(2)}</div>
                <div class="cart-summary-price">£${item.subtotal.toFixed(2)}</div>
            </div>
        </div>
    `).join('');
}

function updateSummaryTotals() {
    const subtotal = cartData.subtotal || 0;
    const discount = cartData.discount || 0;
    const shippingCost = selectedShipping ? selectedShipping.price : 0;
    const total = (subtotal - discount) + shippingCost;
    
    const subtotalEl = document.getElementById('summarySubtotal');
    const shippingEl = document.getElementById('summaryShipping');
    const totalEl = document.getElementById('summaryTotal');
    
    if (subtotalEl) subtotalEl.textContent = `£${subtotal.toFixed(2)}`;
    if (shippingEl) shippingEl.textContent = shippingCost > 0 ? `£${shippingCost.toFixed(2)}` : 'TBD';
    if (totalEl) totalEl.textContent = `£${total.toFixed(2)}`;
    
    const discountRow = document.getElementById('discountRow');
    if (discount > 0 && discountRow) {
        discountRow.classList.remove('hidden');
        const codeEl = document.getElementById('discountCode');
        const discountEl = document.getElementById('summaryDiscount');
        if (codeEl) codeEl.textContent = cartData.coupon_code;
        if (discountEl) discountEl.textContent = `-£${discount.toFixed(2)}`;
    } else if (discountRow) {
        discountRow.classList.add('hidden');
    }
}

// ==================== COUPON ====================

async function applyCoupon() {
    const codeInput = document.getElementById('couponCodeInput');
    const code = codeInput ? codeInput.value.trim() : '';
    
    if (!code) {
        showCouponMessage('Please enter a coupon code', 'error');
        return;
    }
    
    try {
        const response = await fetch('/api/cart/coupon/apply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ code }),
        });
        
        const data = await response.json();
        
        if (data.success) {
            showCouponMessage('Coupon applied successfully!', 'success');
            await loadCartSummary();
        } else {
            showCouponMessage(data.message || 'Invalid coupon code', 'error');
        }
    } catch (error) {
        console.error('Coupon error:', error);
        showCouponMessage('Failed to apply coupon', 'error');
    }
}

function showCouponMessage(message, type) {
    const messageEl = document.getElementById('couponMessage');
    if (!messageEl) return;
    
    messageEl.textContent = message;
    messageEl.className = `coupon-message coupon-message-${type}`;
    messageEl.classList.remove('hidden');
    
    setTimeout(() => {
        if (type === 'success') {
            messageEl.classList.add('hidden');
        }
    }, 5000);
}

// ==================== STEPPER NAVIGATION ====================

function updateStepperStyles() {
    document.querySelectorAll('.step').forEach((el, index) => {
        if (index + 1 < currentStep) {
            el.classList.add('completed');
            el.classList.remove('active');
        } else if (index + 1 === currentStep) {
            el.classList.add('active');
            el.classList.remove('completed');
        } else {
            el.classList.remove('active', 'completed');
        }
    });
}

async function goToStep(step) {
    // Validation before proceeding
    if (step === 2 && !validateShippingInfo()) {
        showNotification('Please fill in all required shipping information', 'error');
        return;
    }
    
    if (step === 3 && !selectedShipping) {
        showNotification('Please select a shipping method', 'error');
        return;
    }
    
    // Save shipping data when moving from step 1
    if (currentStep === 1 && step === 2) {
        saveShippingData();
    }
    
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('active');
    });
    
    // Show target step
    const targetStep = document.getElementById('step' + step);
    if (targetStep) {
        targetStep.classList.remove('hidden');
        targetStep.classList.add('active');
    }
    
    currentStep = step;
    updateStepperStyles();
    
    // Step-specific actions
    if (step === 2) {
        await loadShippingMethods();
    } else if (step === 3) {
        prepareOrderDetails();
    } else if (step === 4) {
        await initializePaymentForCurrentMethod();
    } else if (step === 5) {
        hideOrderSummary();
    } else {
        showOrderSummary();
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goBack() {
    if (currentStep > 1) {
        goToStep(currentStep - 1);
    } else {
        window.history.back();
    }
}

function saveShippingData() {
    const countryCodeSelect = document.getElementById('countryCode');
    const phoneInput = document.getElementById('phone');
    
    shippingData = {
        firstName: document.getElementById('firstName')?.value.trim(),
        lastName: document.getElementById('lastName')?.value.trim(),
        email: document.getElementById('email')?.value.trim(),
        countryCode: countryCodeSelect?.value || '+44',
        phone: phoneInput?.value.trim() || '',
        address: document.getElementById('address')?.value.trim(),
        city: document.getElementById('city')?.value.trim(),
        state: document.getElementById('state')?.value.trim(),
        zipcode: document.getElementById('zipcode')?.value.trim(),
        country: document.getElementById('country')?.value.trim(),
        message: document.getElementById('message')?.value.trim() || '',
    };
}

function validateShippingInfo() {
    const required = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zipcode', 'country'];
    return required.every(id => {
        const el = document.getElementById(id);
        return el && el.value.trim() !== '';
    });
}

function hideOrderSummary() {
    const summary = document.getElementById('orderSummary');
    if (summary) summary.style.display = 'none';
}

function showOrderSummary() {
    const summary = document.getElementById('orderSummary');
    if (summary) summary.style.display = 'block';
}

// ==================== SHIPPING METHODS ====================

async function loadShippingMethods() {
    const country = document.getElementById('country')?.value.trim();
    const state = document.getElementById('state')?.value.trim();
    
    if (!country) {
        showNotification('Please enter your country first', 'error');
        return;
    }
    
    const container = document.getElementById('shippingMethodsContainer');
    if (!container) return;
    
    // Show loading
    container.innerHTML = `
        <div class="loading-state">
            <div class="spinner"></div>
            <p>Loading shipping methods...</p>
        </div>
    `;
    
    try {
        const response = await fetch(`/api/checkout/shipping-methods?country=${encodeURIComponent(country)}&state=${encodeURIComponent(state)}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        });
        const data = await response.json();
        
        if (data.success && data.shipping_methods.length > 0) {
            renderShippingMethods(data.shipping_methods);
        } else {
            container.innerHTML = '<p class="text-center">No shipping methods available for your location</p>';
        }
    } catch (error) {
        console.error('Failed to load shipping methods:', error);
        container.innerHTML = '<p class="error-message">Failed to load shipping methods</p>';
    }
}

function renderShippingMethods(methods) {
    const container = document.getElementById('shippingMethodsContainer');
    if (!container) return;
    
    container.innerHTML = methods.map(method => `
        <label class="shipping-option">
            <input type="radio" name="shipping_method" value="${method.id}" 
                   data-price="${method.price}" 
                   data-name="${method.name}"
                   onchange="selectShippingMethod(${method.id}, '${method.name}', ${method.price})">
            <div class="shipping-option-details">
                <div class="shipping-option-name">${method.name}</div>
                <div class="shipping-option-description">${method.description || ''}</div>
                <div class="shipping-option-time">${method.estimated_delivery || ''}</div>
            </div>
            <div class="shipping-option-price">£${method.price.toFixed(2)}</div>
        </label>
    `).join('');
}

function selectShippingMethod(id, name, price) {
    selectedShipping = { id, name, price };
    updateSummaryTotals();
}

// ==================== ORDER DETAILS ====================

function prepareOrderDetails() {
    const container = document.getElementById('orderDetailsContainer');
    if (!container) return;
    
    const items = cartData.items || [];
    const subtotal = cartData.subtotal || 0;
    const discount = cartData.discount || 0;
    const shippingCost = selectedShipping ? selectedShipping.price : 0;
    const total = (subtotal - discount) + shippingCost;
    
    container.innerHTML = `
        <div class="order-review">
            <div class="order-section">
                <h3 class="section-subtitle">Shipping Address</h3>
                <div class="order-info">
                    <p>${shippingData.firstName} ${shippingData.lastName}</p>
                    <p>${shippingData.address}</p>
                    <p>${shippingData.city}, ${shippingData.state} ${shippingData.zipcode}</p>
                    <p>${shippingData.country}</p>
                    <p>${shippingData.email}</p>
                    <p>${shippingData.phone}</p>
                </div>
            </div>
            
            <div class="order-section">
                <h3 class="section-subtitle">Shipping Method</h3>
                <div class="order-info">
                    <p>${selectedShipping.name} - £${selectedShipping.price.toFixed(2)}</p>
                </div>
            </div>
            
            <div class="order-section">
                <h3 class="section-subtitle">Order Items</h3>
                <div class="order-items">
                    ${items.map(item => `
                        <div class="order-item">
                            <img src="${item.image || '/images/placeholder-book.jpg'}" alt="${item.name}">
                            <div class="order-item-details">
                                <div class="order-item-name">${item.name}</div>
                                <div class="order-item-qty">Qty: ${item.quantity} × £${item.price.toFixed(2)}</div>
                            </div>
                            <div class="order-item-price">£${item.subtotal.toFixed(2)}</div>
                        </div>
                    `).join('')}
                </div>
            </div>
            
            <div class="order-totals">
                <div class="order-total-row">
                    <span>Subtotal:</span>
                    <span>£${subtotal.toFixed(2)}</span>
                </div>
                ${discount > 0 ? `
                    <div class="order-total-row discount">
                        <span>Discount:</span>
                        <span>-£${discount.toFixed(2)}</span>
                    </div>
                ` : ''}
                <div class="order-total-row">
                    <span>Shipping:</span>
                    <span>£${shippingCost.toFixed(2)}</span>
                </div>
                <div class="order-total-row total">
                    <span>Total:</span>
                    <span>£${total.toFixed(2)}</span>
                </div>
            </div>
        </div>
    `;
}

// ==================== PAYMENT ====================

function initializePaymentOptions() {
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            selectedPaymentMethod = this.value;
            updatePaymentUI();
        });
    });
}

function updatePaymentUI() {
    const stripeSection = document.getElementById('stripePaymentSection');
    const paypalSection = document.getElementById('paypalButtonSection');
    
    if (selectedPaymentMethod === 'stripe') {
        if (stripeSection) stripeSection.classList.remove('hidden');
        if (paypalSection) paypalSection.classList.add('hidden');
    } else if (selectedPaymentMethod === 'paypal') {
        if (stripeSection) stripeSection.classList.add('hidden');
        if (paypalSection) paypalSection.classList.remove('hidden');
    }
}

async function initializePaymentForCurrentMethod() {
    // Initialize Stripe on page load
    if (!stripe && STRIPE_KEY) {
        stripe = Stripe(STRIPE_KEY);
    }
    
    if (selectedPaymentMethod === 'stripe') {
        await initializeStripe();
    }
}

async function initializeStripe() {
    if (!STRIPE_KEY) {
        console.error('Stripe key not configured');
        return;
    }
    
    try {
        // Calculate total amount
        const subtotal = cartData.subtotal || 0;
        const discount = cartData.discount || 0;
        const shippingCost = selectedShipping ? selectedShipping.price : 0;
        const total = (subtotal - discount) + shippingCost;
        const amountInCents = Math.round(total * 100);
        
        const response = await fetch('/api/checkout/create-payment-intent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                payment_method: 'stripe',
                amount: amountInCents,
                currency: 'gbp',
                firstName: shippingData.firstName,
                lastName: shippingData.lastName,
                email: shippingData.email,
                phone: shippingData.countryCode + shippingData.phone,
                address: shippingData.address,
                city: shippingData.city,
                state: shippingData.state,
                zipcode: shippingData.zipcode,
                country: shippingData.country,
                shipping_method: selectedShipping?.id || '',
                shipping_cost: shippingCost,
                message: shippingData.message || '',
            }),
        });
        
        const data = await response.json();
        
        if (data.success && data.client_secret) {
            clientSecret = data.client_secret;
            
            // Initialize Stripe Elements
            if (!elements) {
                elements = stripe.elements({ clientSecret });
                paymentElement = elements.create('payment');
                paymentElement.mount('#payment-element');
            }
        } else {
            showNotification(data.message || 'Failed to initialize payment', 'error');
        }
    } catch (error) {
        console.error('Stripe initialization error:', error);
        showNotification('Failed to initialize payment', 'error');
    }
}

async function processPayment() {
    if (selectedPaymentMethod === 'stripe') {
        await processStripePayment();
    }
}

async function processStripePayment() {
    if (!stripe || !elements) {
        showNotification('Payment not initialized', 'error');
        return;
    }
    
    const payBtn = document.getElementById('payBtn');
    const payBtnText = document.getElementById('payBtnText');
    const paySpinner = document.getElementById('paySpinner');
    
    // Show loading
    if (payBtn) payBtn.disabled = true;
    if (payBtnText) payBtnText.textContent = 'Processing...';
    if (paySpinner) paySpinner.classList.remove('hidden');
    
    try {
        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: window.location.origin + '/checkout/success',
            },
        });
        
        if (error) {
            showNotification(error.message, 'error');
            const errorEl = document.getElementById('payment-errors');
            if (errorEl) {
                errorEl.textContent = error.message;
                errorEl.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Payment error:', error);
        showNotification('Payment failed', 'error');
    } finally {
        // Reset button
        if (payBtn) payBtn.disabled = false;
        if (payBtnText) payBtnText.textContent = 'Pay Now';
        if (paySpinner) paySpinner.classList.add('hidden');
    }
}

// Make functions globally available
window.goToStep = goToStep;
window.goBack = goBack;
window.applyCoupon = applyCoupon;
window.selectShippingMethod = selectShippingMethod;
window.processPayment = processPayment;

</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.cart-summary-item {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--color-border);
}

.cart-summary-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--border-radius);
    background: var(--color-bg-light);
}

.cart-summary-details {
    flex: 1;
}

.cart-summary-name {
    font-weight: 600;
    color: var(--color-secondary);
    margin-bottom: var(--spacing-xs);
}

.cart-summary-qty {
    font-size: 0.875rem;
    color: var(--color-text);
}

.cart-summary-price {
    font-weight: 700;
    color: var(--color-secondary);
    margin-top: var(--spacing-xs);
}

.coupon-message {
    font-size: 0.875rem;
    margin-top: var(--spacing-sm);
}

.coupon-message-success {
    color: #10b981;
}

.coupon-message-error {
    color: #ef4444;
}

.shipping-option {
    border: 2px solid var(--color-border);
    border-radius: var(--border-radius);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-md);
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.shipping-option:hover {
    border-color: var(--color-gold);
}

.shipping-option input[type="radio"]:checked + .shipping-option-details {
    color: var(--color-gold);
}

.shipping-option input[type="radio"] {
    width: 20px;
    height: 20px;
    accent-color: var(--color-gold);
}

.shipping-option-details {
    flex: 1;
}

.shipping-option-name {
    font-weight: 600;
    color: var(--color-secondary);
    margin-bottom: var(--spacing-xs);
}

.shipping-option-description {
    font-size: 0.875rem;
    color: var(--color-text);
}

.shipping-option-time {
    font-size: 0.875rem;
    color: var(--color-text-light);
    margin-top: var(--spacing-xs);
}

.shipping-option-price {
    font-weight: 700;
    color: var(--color-secondary);
    font-size: 1.125rem;
}

.order-review {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xl);
}

.order-section {
    padding: var(--spacing-lg);
    background: var(--color-bg-light);
    border-radius: var(--border-radius);
}

.section-subtitle {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-secondary);
    margin-bottom: var(--spacing-md);
}

.order-info p {
    margin-bottom: var(--spacing-xs);
    color: var(--color-text);
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.order-item {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.order-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: var(--border-radius);
}

.order-item-details {
    flex: 1;
}

.order-item-name {
    font-weight: 600;
    color: var(--color-secondary);
}

.order-item-qty {
    font-size: 0.875rem;
    color: var(--color-text);
}

.order-item-price {
    font-weight: 700;
    color: var(--color-secondary);
}

.order-totals {
    padding: var(--spacing-lg);
    background: white;
    border: 2px solid var(--color-border);
    border-radius: var(--border-radius);
}

.order-total-row {
    display: flex;
    justify-content: space-between;
    padding: var(--spacing-sm) 0;
    color: var(--color-text);
}

.order-total-row.discount {
    color: #10b981;
}

.order-total-row.total {
    border-top: 2px solid var(--color-border);
    margin-top: var(--spacing-sm);
    padding-top: var(--spacing-md);
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-secondary);
}
</style>
