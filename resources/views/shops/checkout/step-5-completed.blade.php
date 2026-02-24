<div id="step5" class="step-content hidden">
    <div class="text-center py-16 px-5 max-w-2xl mx-auto">
        <div class="success-icon w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-12 h-12 stroke-white stroke-[3] fill-none" viewBox="0 0 52 52">
                <polyline points="14 27 22 35 38 19" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        
        <h2 class="text-4xl font-bold mb-4 text-gray-900">Order Completed Successfully!</h2>
        <p class="text-base text-gray-600 mb-8 leading-relaxed">Thank you for your purchase. A confirmation email has been sent to your email address.</p>
        
        <div class="text-2xl font-bold text-orange-600 my-5" id="completedOrderNumber">#ORD-XXXXX</div>
        
        <p class="text-base text-gray-600 mb-10 leading-relaxed">You can track your order status using the order number above</p>
        
        <div class="flex flex-col md:flex-row gap-4 justify-center mt-10">
            <a href="{{ route('home') }}" class="btn-hover-effect bg-orange-600 text-white font-semibold py-3 px-8 rounded active:scale-[0.98] transition-transform">
                <span class="btn-content">Go to Homepage</span>
            </a>
            <button class="bg-white text-gray-900 border-2 border-gray-900 font-semibold py-3 px-8 rounded hover:bg-gray-900 hover:text-white transition-all" onclick="window.print()">Download Receipt</button>
        </div>
    </div>
</div>