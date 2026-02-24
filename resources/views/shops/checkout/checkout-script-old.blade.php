<script>
// Configuration
const STRIPE_KEY = '{{ $stripeKey }}';
const PAYPAL_CLIENT_ID = '{{ config("services.paypal.client_id") }}';


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
    initializeStripe();
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

// ==================== CART & SUMMARY ====================

async function loadCartSummary() {
    try {
        const response = await fetch('/api/checkout/cart-summary', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        const data = await response.json();
        
        if (data.success) {
            cartData = data;
            renderCartItems(data.items);
            updateSummaryTotals();
        } else {
            alertModal('Your cart is empty', 'error');
            setTimeout(() => window.location.href = '{{ route("home") }}', 2000);
        }
    } catch (error) {
        console.error('Failed to load cart:', error);
        alertModal('Failed to load cart data', 'error');
    }
}

function renderCartItems(items) {
    const container = document.getElementById('cartItemsList');
    if (!container || !items) return;
    
    container.innerHTML = items.map(item => `
        <div class="flex gap-4 mb-5 pb-5 border-b border-gray-300">
            <img src="${item.image || 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\'%3E%3Crect fill=\'%23f0f0f0\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\'%3EBook%3C/text%3E%3C/svg%3E'}" 
                 alt="${item.name}" 
                 class="w-20 h-20 object-cover rounded bg-gray-100">
            <div class="flex-1">
                <div class="font-semibold mb-1 text-gray-900">${item.name}</div>
                <div class="text-sm text-gray-600">Qty: ${item.quantity} × £${item.price.toFixed(2)}</div>
                <div class="font-bold text-gray-900 mt-2">£${item.subtotal.toFixed(2)}</div>
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
    messageEl.className = `text-sm mt-2 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
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
        const stepNumber = el.querySelector('.step-number');
        const stepLabel = el.querySelector('.step-label');
        
        if (index + 1 < currentStep) {
            // Completed steps
            stepNumber.className = 'step-number w-8 h-8 rounded-full bg-gray-900 border-2 border-gray-900 flex items-center justify-center font-semibold text-white transition-all';
            stepLabel.className = 'step-label mt-2 text-sm text-gray-900 text-center font-medium';
        } else if (index + 1 === currentStep) {
            // Current step
            stepNumber.className = 'step-number w-8 h-8 rounded-full bg-orange-600 border-2 border-orange-600 flex items-center justify-center font-semibold text-white transition-all';
            stepLabel.className = 'step-label mt-2 text-sm text-gray-900 text-center font-medium';
        } else {
            // Upcoming steps
            stepNumber.className = 'step-number w-8 h-8 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center font-semibold text-gray-400 transition-all';
            stepLabel.className = 'step-label mt-2 text-sm text-gray-400 text-center font-medium';
        }
    });
}

async function goToStep(step) {
    // Validation before proceeding
    if (step === 2 && !validateShippingInfo()) {
        alertModal('Please fill in all required shipping information', 'warning');
        return;
    }
    
    if (step === 3 && !selectedShipping) {
        alertModal('Please select a shipping method', 'warning');
        return;
    }
    
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Show target step
    const targetStep = document.getElementById('step' + step);
    if (targetStep) {
        targetStep.classList.remove('hidden');
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

function validateShippingInfo() {
    const required = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zipcode', 'country'];
    return required.every(id => {
        const el = document.getElementById(id);
        return el && el.value.trim() !== '';
    });
}

function hideOrderSummary() {
    const summary = document.getElementById('orderSummary');
    const divider = document.getElementById('verticalDivider');
    const mainContent = document.getElementById('mainContent');
    
    if (summary) summary.classList.add('hidden');
    if (divider) divider.classList.add('hidden');
    if (mainContent) mainContent.className = 'block';
}

function showOrderSummary() {
    const summary = document.getElementById('orderSummary');
    const divider = document.getElementById('verticalDivider');
    const mainContent = document.getElementById('mainContent');
    
    if (summary) summary.classList.remove('hidden');
    if (divider) divider.classList.remove('hidden');
    if (mainContent) mainContent.className = 'grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-8 lg:gap-16 relative';
}

// ==================== SHIPPING METHODS ====================

async function loadShippingMethods() {
    const country = document.getElementById('country')?.value.trim();
    const state = document.getElementById('state')?.value.trim();
    
    if (!country) {
        alertModal('Please enter your country first', 'warning');
        return;
    }
    
    const container = document.getElementById('shippingMethodsContainer');
    if (!container) return;
    
    // Show loading
    container.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto"></div>
            <p class="text-gray-600 mt-4">Loading shipping methods...</p>
        </div>
    `;
    
    try {
        const response = await fetch(`/api/checkout/shipping-methods?country=${encodeURIComponent(country)}&state=${encodeURIComponent(state)}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        const data = await response.json();
        
        if (data.success && data.shipping_methods.length > 0) {
            renderShippingMethods(data.shipping_methods);
        } else {
            container.innerHTML = '<p class="text-center text-gray-600 py-8">No shipping methods available for your location</p>';
        }
    } catch (error) {
        console.error('Failed to load shipping methods:', error);
        container.innerHTML = '<p class="text-center text-red-600 py-8">Failed to load shipping methods</p>';
    }
}

function renderShippingMethods(methods) {
    const container = document.getElementById('shippingMethodsContainer');
    if (!container) return;
    
    container.innerHTML = methods.map((method, index) => `
        <label class="shipping-option border-2 border-gray-300 rounded p-5 cursor-pointer transition-all hover:border-orange-600" 
               data-method='${JSON.stringify(method)}'>
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <input type="radio" name="shipping" value="${method.id}" 
                           class="w-5 h-5 text-orange-600 focus:ring-orange-600" 
                           ${index === 0 ? 'checked' : ''}>
                    <div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">${method.name}</h4>
                        <p class="text-sm text-gray-600">${method.delivery_time || 'Standard delivery'}</p>
                        ${method.description ? `<p class="text-xs text-gray-500 mt-1">${method.description}</p>` : ''}
                    </div>
                </div>
                <div class="text-lg font-bold text-orange-600">${method.formatted_price}</div>
            </div>
        </label>
    `).join('');
    
    // Setup click handlers
    document.querySelectorAll('.shipping-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.shipping-option').forEach(opt => {
                opt.classList.remove('border-orange-600', 'shadow-lg', 'shadow-orange-100');
                opt.classList.add('border-gray-300');
            });
            
            this.classList.remove('border-gray-300');
            this.classList.add('border-orange-600', 'shadow-lg', 'shadow-orange-100');
            this.querySelector('input[type="radio"]').checked = true;
            
            selectedShipping = JSON.parse(this.dataset.method);
            updateSummaryTotals();
        });
    });
    
    // Auto-select first method
    if (methods.length > 0) {
        selectedShipping = methods[0];
        updateSummaryTotals();
        const proceedBtn = document.getElementById('proceedToDetailsBtn');
        if (proceedBtn) proceedBtn.classList.remove('hidden');
    }
}

// ==================== ORDER DETAILS ====================

function prepareOrderDetails() {
    const container = document.getElementById('orderDetailsContent');
    if (!container) return;
    
    // Gather shipping info
    shippingData = {
        firstName: document.getElementById('firstName')?.value,
        lastName: document.getElementById('lastName')?.value,
        email: document.getElementById('email')?.value,
        phone: (document.getElementById('countryCode')?.value || '') + ' ' + (document.getElementById('phone')?.value || ''),
        address: document.getElementById('address')?.value,
        city: document.getElementById('city')?.value,
        state: document.getElementById('state')?.value,
        zipcode: document.getElementById('zipcode')?.value,
        country: document.getElementById('country')?.value,
        message: document.getElementById('message')?.value,
    };
    
    const subtotal = cartData.subtotal || 0;
    const discount = cartData.discount || 0;
    const shippingCost = selectedShipping ? selectedShipping.price : 0;
    const total = (subtotal - discount) + shippingCost;
    
    container.innerHTML = `
        <div class="mb-8 pb-8 border-b border-gray-300">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Products</h3>
            ${cartData.items.map(item => `
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <p class="font-medium text-gray-900">${item.name}</p>
                        <p class="text-sm text-gray-600">Qty: ${item.quantity} × £${item.price.toFixed(2)}</p>
                    </div>
                    <p class="font-semibold text-gray-900">£${item.subtotal.toFixed(2)}</p>
                </div>
            `).join('')}
        </div>

        <div class="mb-8 pb-8 border-b border-gray-300">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Shipping Information</h3>
            <p class="text-gray-600 leading-relaxed">
                <strong class="text-gray-900">Name:</strong> ${shippingData.firstName} ${shippingData.lastName}<br>
                <strong class="text-gray-900">Email:</strong> ${shippingData.email}<br>
                <strong class="text-gray-900">Phone:</strong> ${shippingData.phone}<br>
                <strong class="text-gray-900">Address:</strong> ${shippingData.address}, ${shippingData.city}, ${shippingData.state} ${shippingData.zipcode}, ${shippingData.country}
            </p>
        </div>

        <div class="mb-8 pb-8 border-b border-gray-300">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Shipping Method</h3>
            <p class="text-gray-600">
                <strong class="text-gray-900">${selectedShipping.name}</strong><br>
                ${selectedShipping.delivery_time || ''}<br>
                Cost: <strong class="text-gray-900">${selectedShipping.formatted_price}</strong>
            </p>
        </div>

        <div class="bg-gray-50 p-6 rounded">
            <div class="flex justify-between mb-3">
                <span class="text-gray-600">Subtotal:</span>
                <span class="text-gray-900 font-semibold">£${subtotal.toFixed(2)}</span>
            </div>
            ${discount > 0 ? `
                <div class="flex justify-between mb-3">
                    <span class="text-green-600">Discount:</span>
                    <span class="text-green-600 font-semibold">-£${discount.toFixed(2)}</span>
                </div>
            ` : ''}
            <div class="flex justify-between mb-3">
                <span class="text-gray-600">Shipping:</span>
                <span class="text-gray-900 font-semibold">£${shippingCost.toFixed(2)}</span>
            </div>
            <div class="flex justify-between text-xl font-bold text-gray-900 pt-3 border-t-2 border-gray-300">
                <span>Total:</span>
                <span class="text-orange-600">£${total.toFixed(2)}</span>
            </div>
        </div>
    `;
}

// ==================== PAYMENT METHODS ====================

function initializePaymentOptions() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const stripeSection = document.getElementById('stripePaymentSection');
    const paypalSection = document.getElementById('paypalButtonSection');
    
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            paymentOptions.forEach(opt => {
                opt.classList.remove('border-orange-600', 'shadow-lg');
                opt.classList.add('border-gray-300');
            });
            
            this.classList.remove('border-gray-300');
            this.classList.add('border-orange-600', 'shadow-lg');
            
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            selectedPaymentMethod = radio.value;
            
            // Show/hide payment sections
            if (selectedPaymentMethod === 'stripe') {
                stripeSection?.classList.remove('hidden');
                paypalSection?.classList.add('hidden');
            } else {
                stripeSection?.classList.add('hidden');
                paypalSection?.classList.remove('hidden');
                initializePayPal();
            }
        });
    });
}

function initializeStripe() {
    if (typeof Stripe === 'undefined') {
        console.error('Stripe.js not loaded');
        return;
    }
    
    stripe = Stripe(STRIPE_KEY);
}

async function initializePaymentForCurrentMethod() {
    if (selectedPaymentMethod === 'stripe') {
        await createPaymentIntent();
    } else {
        initializePayPal();
    }
}

async function createPaymentIntent() {
    const container = document.getElementById('payment-element');
    if (!container) return;
    
    // Show loading
    container.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto"></div>
            <p class="text-gray-600 mt-4">Initializing payment...</p>
        </div>
    `;
    
    try {
        const subtotal = cartData.subtotal || 0;
        const discount = cartData.discount || 0;
        const shippingCost = selectedShipping ? selectedShipping.price : 0;
        const totalAmount = (subtotal - discount) + shippingCost;
        const amountInCents = Math.round(totalAmount * 100);
        
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
                ...shippingData,
                shipping_method: selectedShipping.name,
                shipping_cost: selectedShipping.price,
            }),
        });
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Failed to create payment intent');
        }
        
        clientSecret = data.client_secret;
        
        // Initialize Stripe Elements with Payment Element
        const appearance = {
            theme: 'stripe',
            variables: {
                colorPrimary: '#ea580c',
                colorBackground: '#ffffff',
                colorText: '#1f2937',
                colorDanger: '#ef4444',
                fontFamily: 'system-ui, sans-serif',
                spacingUnit: '4px',
                borderRadius: '8px',
            },
        };
        
        elements = stripe.elements({ 
            clientSecret,
            appearance 
        });
        
        // Create and mount the Payment Element
        paymentElement = elements.create('payment', {
            layout: {
                type: 'tabs',
                defaultCollapsed: false,
            },
        });
        
        container.innerHTML = '';
        paymentElement.mount('#payment-element');
        
    } catch (error) {
        console.error('Payment intent error:', error);
        container.innerHTML = `
            <div class="text-center py-8 text-red-600">
                <p>Failed to initialize payment: ${error.message}</p>
                <button onclick="createPaymentIntent()" class="mt-4 text-orange-600 hover:text-orange-700 underline">
                    Try Again
                </button>
            </div>
        `;
    }
}

function initializePayPal() {
    const container = document.getElementById('paypal-button-container');
    if (!container || typeof paypal === 'undefined') return;
    
    // Clear existing buttons
    container.innerHTML = '';
    
    const subtotal = cartData.subtotal || 0;
    const discount = cartData.discount || 0;
    const shippingCost = selectedShipping ? selectedShipping.price : 0;
    const total = ((subtotal - discount) + shippingCost).toFixed(2);
    
    paypal.Buttons({
        createOrder: async function(data, actions) {
            try {
                const response = await fetch('/api/checkout/create-payment-intent', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        payment_method: 'paypal',
                        amount: Math.round(parseFloat(total) * 100),
                        currency: 'gbp',
                        ...shippingData,
                        shipping_method: selectedShipping.name,
                        shipping_cost: selectedShipping.price,
                    }),
                });
                
                const result = await response.json();
                if (result.success) {
                    return result.order_id;
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                alertModal('Failed to initialize PayPal payment: ' + error.message, 'error');
                throw error;
            }
        },
        onApprove: async function(data, actions) {
            await processOrder('paypal', null, data.orderID);
        },
        onError: function(err) {
            console.error('PayPal error:', err);
            alertModal('PayPal payment failed. Please try again.', 'error');
        }
    }).render('#paypal-button-container');
}

// ==================== PAYMENT PROCESSING ====================

async function processPayment() {
    if (selectedPaymentMethod === 'stripe') {
        await processStripePayment();
    } else {
        // PayPal is handled by PayPal buttons
        alertModal('Please use the PayPal button above to complete payment', 'info');
    }
}

async function processStripePayment() {
    const payBtn = document.getElementById('payBtn');
    const btnText = document.getElementById('payBtnText');
    const spinner = document.getElementById('paySpinner');
    
    // Disable button
    payBtn.disabled = true;
    if (btnText) btnText.classList.add('hidden');
    if (spinner) spinner.classList.remove('hidden');
    
    try {
        // Confirm payment with Payment Element
        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: window.location.href,
                payment_method_data: {
                    billing_details: {
                        name: `${shippingData.firstName} ${shippingData.lastName}`,
                        email: shippingData.email,
                        phone: shippingData.phone,
                        address: {
                            line1: shippingData.address,
                            city: shippingData.city,
                            state: shippingData.state,
                            postal_code: shippingData.zipcode,
                            country: shippingData.country,
                        },
                    },
                },
            },
            redirect: 'if_required',
        });
        
        if (error) {
            throw new Error(error.message);
        }
        
        if (paymentIntent && paymentIntent.status === 'succeeded') {
            await processOrder('stripe', paymentIntent.id);
        } else if (paymentIntent && paymentIntent.status === 'requires_action') {
            // Handle additional authentication if needed
            const { error: confirmError } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: window.location.href,
                },
            });
            
            if (confirmError) {
                throw new Error(confirmError.message);
            }
        } else {
            throw new Error('Payment not completed');
        }
        
    } catch (error) {
        console.error('Payment error:', error);
        alertModal(error.message || 'Payment failed. Please try again.', 'error');
        
        // Re-enable button
        payBtn.disabled = false;
        if (btnText) btnText.classList.remove('hidden');
        if (spinner) spinner.classList.add('hidden');
    }
}

async function processOrder(paymentMethod, paymentIntentId = null, paypalOrderId = null) {
    try {
        const response = await fetch('/api/checkout/process-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                payment_method: paymentMethod,
                payment_intent_id: paymentIntentId,
                paypal_order_id: paypalOrderId,
                ...shippingData,
                shipping_method: selectedShipping.name,
                shipping_cost: selectedShipping.price,
            }),
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update order number in completion page
            const orderNumberEl = document.getElementById('completedOrderNumber');
            if (orderNumberEl) {
                orderNumberEl.textContent = data.order_number;
            }
            
            goToStep(5);
        } else {
            throw new Error(data.message || 'Order processing failed');
        }
        
    } catch (error) {
        console.error('Order processing error:', error);
        alertModal(error.message || 'Order processing failed. Please contact support.', 'error');
    }
}

// ==================== HELPER FUNCTIONS ====================

function formatCurrency(amount) {
    return `£${parseFloat(amount).toFixed(2)}`;
}
</script>