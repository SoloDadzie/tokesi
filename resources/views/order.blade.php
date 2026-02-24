@extends('layouts.app')

@section('title', 'Track Your Order')

@section('content')

<!---------------HERO BANNER---------------------->

<section class="p-hero bg-gray-100 flex flex-col justify-center items-center gap-2 w-full h-[30vh] mt-[75px]">
    <h1 class="text-2xl font-extrabold">Track Your Order</h1>

    <div class="b-crumb flex gap-1 text-sm text-gray-700">
        <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
        <span class="text-gray-500">/</span>
        <a href="{{ route('order') }}" class="hover:text-black transition-colors">Track Order</a>
    </div>
</section>


<section class="w-full px-5 lg:px-20 overflow-hidden py-12 bg-gray-50" x-data="trackOrder()">
    <!-- Track Your Order Form -->
    <div class="max-w-2xl mx-auto mb-10">
        <h2 class="text-3xl font-manrope font-bold text-[#21263a] mb-4">Track Your Order</h2>
        <p class="text-sm font-manrope text-gray-700 mb-6">
            Please enter your email and the order number sent to your email when you placed the order.
        </p>

        <form @submit.prevent="fetchOrder" class="flex flex-col gap-4">
            <input type="email" placeholder="Email" x-model="email" required
                class="px-4 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
            <input type="text" placeholder="Order Number" x-model="orderNumber" required
                class="px-4 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
            <button type="submit" :disabled="loading"
                class="bg-[#21263a] text-white px-4 py-2 rounded-[4px] font-semibold hover:bg-[#373d57] transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!loading">Track Order</span>
                <span x-show="loading">Loading...</span>
            </button>
        </form>
    </div>

    <!-- Order Info -->
    <template x-if="orderFetched">
        <div class="max-w-4xl mx-auto flex flex-col gap-8">

            <!-- Order Progress Stepper -->
            <div class="flex justify-center items-center gap-4 md:gap-8 relative">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex flex-col items-center relative z-10">
                        <!-- Progress Line -->
                        <template x-if="index < steps.length - 1">
                            <div class="hidden md:block absolute h-1 top-6 left-[calc(50%+24px)] z-0"
                                :class="steps[index + 1].completed ? 'bg-[#d67a00]' : 'bg-gray-300'"
                                :style="`width: ${index < steps.length - 1 ? 'calc(100% + 2rem)' : '0'}`"></div>
                        </template>

                        <div :class="{'bg-[#d67a00] text-white': step.completed, 'bg-gray-300 text-gray-600': !step.completed}" 
                            class="w-12 h-12 flex items-center justify-center rounded-full z-10 transition-colors">
                            <template x-if="step.name === 'Order Processed'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                                </svg>
                            </template>
                            <template x-if="step.name === 'Order Shipped'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </template>
                            <template x-if="step.name === 'Order Delivered'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </template>
                        </div>
                        <span class="text-xs md:text-sm font-manrope text-gray-700 text-center mt-2 max-w-[80px]" x-text="step.name"></span>
                    </div>
                </template>
            </div>

            <!-- Order Details -->
            <div class="border border-gray-300 rounded-[4px] p-4">
                <h3 class="text-[#21263a] font-semibold mb-4">Order Information</h3>

                <div class="space-y-4">
                    <template x-for="item in order.items" :key="item.id">
                        <div class="flex items-center justify-between border-b border-gray-200 pb-2 last:border-b-0">
                            <div class="flex items-center gap-4">
                                <img :src="item.image" alt="" class="w-16 h-16 object-cover rounded-[4px]">
                                <div>
                                    <p class="text-sm text-gray-700" x-text="item.name"></p>
                                    <p class="text-xs text-gray-500" x-text="'Quantity: ' + item.quantity"></p>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-[#21263a]" x-text="item.price"></p>
                        </div>
                    </template>
                </div>

                <!-- Shipping Method and Fee -->
                <div class="mt-4 border-t border-gray-200 pt-4 text-sm text-gray-700 space-y-1">
                    <p><strong>Shipping Method:</strong> <span x-text="order.shipping_method || 'Standard Shipping'"></span></p>
                    <p><strong>Shipping Fee:</strong> <span x-text="order.shipping_fee || '£0.00'"></span></p>
                </div>

                <!-- Tracking Info Collapsible -->
                <div class="mt-4 border-t border-gray-200 pt-4" x-data="{ openTracking: true }" x-show="order.tracking_number || order.shipping_company">
                    <button @click="openTracking = !openTracking" class="w-full flex justify-between items-center text-[#21263a] font-semibold mb-2 lg:mb-4">
                        <span>Tracking Information</span>
                        <svg :class="{'rotate-180': openTracking}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openTracking" x-transition class="text-sm text-gray-700 space-y-2">
                        <p x-show="order.shipping_company"><strong>Shipped By:</strong> <span x-text="order.shipping_company"></span></p>
                        <p x-show="order.tracking_number"><strong>Tracking Number:</strong> <span x-text="order.tracking_number"></span></p>
                        <p x-show="order.tracking_link"><strong>Tracking Link:</strong> <a :href="order.tracking_link" target="_blank" class="text-[#d67a00] underline">Track Package</a></p>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="border border-gray-300 rounded-[4px] p-4" x-data="{ open: true, confirmUpdate: false, confirmCancel: false }">
                <p class="text-xs text-[#d67a00] mb-2">Shipping Address can only be edited if order is not shipped yet</p>
                <button @click="open = !open" class="w-full flex justify-between items-center text-[#21263a] font-semibold mb-2 lg:mb-4">
                    <span>Delivery Information</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <input type="text" placeholder="First Name" x-model="order.delivery.firstName" disabled
                        class="px-3 py-2 border border-gray-300 rounded-[4px] bg-gray-100 cursor-not-allowed">
                    <input type="text" placeholder="Last Name" x-model="order.delivery.lastName" disabled
                        class="px-3 py-2 border border-gray-300 rounded-[4px] bg-gray-100 cursor-not-allowed">
                    <input type="email" placeholder="Email" x-model="order.delivery.email" :disabled="order.shipped"
                        :class="order.shipped ? 'bg-gray-100 cursor-not-allowed' : ''"
                        class="px-3 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
                    <input type="text" placeholder="Shipping Address" x-model="order.delivery.address" :disabled="order.shipped"
                        :class="order.shipped ? 'bg-gray-100 cursor-not-allowed' : ''"
                        class="col-span-2 px-3 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
                    <input type="text" placeholder="Country" x-model="order.delivery.country" :disabled="order.shipped"
                        :class="order.shipped ? 'bg-gray-100 cursor-not-allowed' : ''"
                        class="px-3 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
                    <input type="text" placeholder="State" x-model="order.delivery.state" :disabled="order.shipped"
                        :class="order.shipped ? 'bg-gray-100 cursor-not-allowed' : ''"
                        class="px-3 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
                    <input type="text" placeholder="City" x-model="order.delivery.city" :disabled="order.shipped"
                        :class="order.shipped ? 'bg-gray-100 cursor-not-allowed' : ''"
                        class="px-3 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
                    <input type="text" placeholder="Zipcode" x-model="order.delivery.zipcode" :disabled="order.shipped"
                        :class="order.shipped ? 'bg-gray-100 cursor-not-allowed' : ''"
                        class="px-3 py-2 border border-gray-300 rounded-[4px] focus:outline-none focus:ring-2 focus:ring-[#d67a00]">
                </div>

                <div class="mt-4 flex gap-3">
                    <button @click="confirmUpdate = true" :disabled="order.shipped || updating"
                        class="bg-[#21263a] text-white px-4 py-2 rounded-[4px] font-semibold hover:bg-[#373d57] transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!updating">Update</span>
                        <span x-show="updating">Updating...</span>
                    </button>

                    <button @click="confirmCancel = true" :disabled="order.shipped || cancelling"
                        class="bg-red-600 text-white px-4 py-2 rounded-[4px] font-semibold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        x-show="!order.shipped">
                        <span x-show="!cancelling">Cancel Order</span>
                        <span x-show="cancelling">Cancelling...</span>
                    </button>
                </div>

                <!-- Confirm Update Modal -->
                <div x-show="confirmUpdate" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-[4px] p-6 max-w-sm w-full text-center shadow-lg">
                        <p class="text-gray-700 mb-4">Are you sure you want to update your address?</p>
                        <div class="flex justify-center gap-3">
                            <button @click="updateDelivery(); confirmUpdate=false"
                                class="bg-[#21263a] text-white px-4 py-2 rounded-[4px] font-semibold hover:bg-[#373d57] transition-colors">
                                Confirm
                            </button>
                            <button @click="confirmUpdate=false"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-[4px] font-semibold hover:bg-gray-400 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Confirm Cancel Modal -->
                <div x-show="confirmCancel" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-[4px] p-6 max-w-sm w-full text-center shadow-lg">
                        <p class="text-gray-700 mb-4">Are you sure you want to cancel this order? A refund will be processed if applicable.</p>
                        <div class="flex justify-center gap-3">
                            <button @click="cancelOrder(); confirmCancel=false"
                                class="bg-red-600 text-white px-4 py-2 rounded-[4px] font-semibold hover:bg-red-700 transition-colors">
                                Confirm
                            </button>
                            <button @click="confirmCancel=false"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-[4px] font-semibold hover:bg-gray-400 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Toast Feedback - FIXED: Higher z-index and better positioning -->
    <div x-show="showFeedback" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         :class="feedbackType === 'error' ? 'bg-red-600' : 'bg-green-600'"
         class="fixed top-24 right-5 text-white px-6 py-3 rounded-lg shadow-2xl z-[9999] max-w-md"
         style="z-index: 9999 !important;">
        <div class="flex items-center gap-3">
            <!-- Success Icon -->
            <svg x-show="feedbackType === 'success'" class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <!-- Error Icon -->
            <svg x-show="feedbackType === 'error'" class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <p class="font-medium" x-text="feedbackMessage"></p>
        </div>
    </div>
</section>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    function trackOrder() {
        return {
            email: '',
            orderNumber: '',
            originalEmail: '',
            orderFetched: false,
            loading: false,
            updating: false,
            cancelling: false,
            showFeedback: false,
            feedbackMessage: '',
            feedbackType: 'success',
            order: {
                shipped: false,
                shipping_company: '',
                tracking_number: '',
                tracking_link: '',
                shipping_method: '',
                shipping_fee: '',
                items: [],
                delivery: {
                    firstName: '',
                    lastName: '',
                    email: '',
                    address: '',
                    country: '',
                    state: '',
                    city: '',
                    zipcode: '',
                }
            },
            steps: [],

            async fetchOrder() {
                this.loading = true;
                try {
                    const response = await fetch('/api/orders/track', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            email: this.email,
                            order_number: this.orderNumber
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.order = data.order;
                        this.steps = data.order.steps;
                        this.originalEmail = this.email;
                        this.orderFetched = true;
                        this.showToast('Order found successfully!', 'success');
                    } else {
                        // Better error message
                        this.showToast(data.message || 'Incorrect email or order number. Please check and try again.', 'error');
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    this.showToast('Unable to connect. Please check your internet connection and try again.', 'error');
                } finally {
                    this.loading = false;
                }
            },

            async updateDelivery() {
                this.updating = true;
                try {
                    const response = await fetch(`/api/orders/${this.orderNumber}/update-address`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            original_email: this.originalEmail,
                            email: this.order.delivery.email,
                            address: this.order.delivery.address,
                            country: this.order.delivery.country,
                            state: this.order.delivery.state,
                            city: this.order.delivery.city,
                            zipcode: this.order.delivery.zipcode,
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.originalEmail = this.order.delivery.email;
                        this.showToast(data.message || 'Address updated successfully!', 'success');
                    } else {
                        this.showToast(data.message || 'Failed to update address. Please try again.', 'error');
                    }
                } catch (error) {
                    console.error('Update error:', error);
                    this.showToast('Unable to update address. Please try again.', 'error');
                } finally {
                    this.updating = false;
                }
            },

            async cancelOrder() {
                this.cancelling = true;
                try {
                    const response = await fetch(`/api/orders/${this.orderNumber}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showToast(data.message || 'Order cancelled successfully. Refund will be processed within 5-10 business days.', 'success');
                        // Refresh order data
                        setTimeout(() => {
                            this.fetchOrder();
                        }, 2000);
                    } else {
                        this.showToast(data.message || 'Failed to cancel order. Please try again.', 'error');
                    }
                } catch (error) {
                    console.error('Cancel error:', error);
                    this.showToast('Unable to cancel order. Please try again.', 'error');
                } finally {
                    this.cancelling = false;
                }
            },

            showToast(message, type = 'success') {
                this.feedbackMessage = message;
                this.feedbackType = type;
                this.showFeedback = true;
                setTimeout(() => this.showFeedback = false, 5000);
            }
        }
    }
</script>

@endsection