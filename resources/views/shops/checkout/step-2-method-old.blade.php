<div id="step2" class="step-content hidden">
    <h2 class="text-3xl font-bold mb-8 text-gray-900">Shipping Method</h2>
    <p class="text-gray-600 mb-5">Select your preferred shipping method</p>
    
    <div id="shippingMethodsContainer" class="flex flex-col gap-4">
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto"></div>
            <p class="text-gray-600 mt-4">Loading shipping methods...</p>
        </div>
    </div>

    <button type="button" id="proceedToDetailsBtn" class="btn-hover-effect w-full bg-orange-600 text-white font-semibold py-3 px-10 rounded mt-8 active:scale-[0.98] transition-transform hidden" onclick="goToStep(3)">
        <span class="btn-content">Proceed to Order Details</span>
    </button>
</div>