<div id="orderSummary" class="sticky top-5 h-fit">
    <h3 class="text-2xl font-bold mb-6 text-gray-900">Order Summary</h3>
    
    <div id="cartItemsList"></div>

    <div class="h-px bg-gray-300 my-5"></div>

    <div class="flex justify-between my-4 text-gray-600">
        <span>Subtotal:</span>
        <span id="summarySubtotal">£0.00</span>
    </div>
    
    <div id="discountRow" class="flex justify-between my-4 text-green-600 hidden">
        <span>Discount (<span id="discountCode"></span>):</span>
        <span id="summaryDiscount">-£0.00</span>
    </div>
    
    <div class="flex justify-between my-4 text-gray-600">
        <span>Shipping:</span>
        <span id="summaryShipping">£0.00</span>
    </div>
    
    <div class="flex justify-between text-xl font-bold text-gray-900 mt-5 pt-5 border-t-2 border-gray-300">
        <span>Total:</span>
        <span id="summaryTotal">£0.00</span>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-300">
        <div class="flex gap-2">
            <input type="text" placeholder="Enter Coupon Code" id="couponCodeInput" class="flex-1 px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            <button id="applyCouponBtn" class="bg-gray-900 text-white font-semibold px-5 py-3 rounded hover:bg-orange-600 transition-colors whitespace-nowrap" onclick="applyCoupon()">Apply</button>
        </div>
        <div id="couponMessage" class="text-sm mt-2 hidden"></div>
    </div>
</div>