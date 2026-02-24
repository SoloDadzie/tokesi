<div id="orderSummary" class="order-summary">
    <h3 class="order-summary-title">Order Summary</h3>
    
    <div id="cartItemsList" class="cart-items-list"></div>

    <div class="summary-divider"></div>

    <div class="summary-row">
        <span>Subtotal:</span>
        <span id="summarySubtotal">£0.00</span>
    </div>
    
    <div id="discountRow" class="summary-row discount hidden">
        <span>Discount (<span id="discountCode"></span>):</span>
        <span id="summaryDiscount">-£0.00</span>
    </div>
    
    <div class="summary-row">
        <span>Shipping:</span>
        <span id="summaryShipping">£0.00</span>
    </div>
    
    <div class="summary-total">
        <span>Total:</span>
        <span id="summaryTotal">£0.00</span>
    </div>

    <div class="coupon-section">
        <div class="coupon-input-group">
            <input type="text" placeholder="Enter Coupon Code" id="couponCodeInput" class="form-input">
            <button id="applyCouponBtn" class="btn btn-secondary" onclick="applyCoupon()">Apply</button>
        </div>
        <div id="couponMessage" class="hidden"></div>
    </div>
</div>
<?php /**PATH /Users/solob/dev/tokesi/Tokesi/resources/views/shops/checkout/order-summary.blade.php ENDPATH**/ ?>