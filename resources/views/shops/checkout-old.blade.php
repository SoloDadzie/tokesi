@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes checkmark {
            to {
                stroke-dashoffset: 0;
            }
        }

        #step4 .spinner {
            animation: spin 0.8s linear infinite;
        }

        #step5 .success-icon {
            animation: scaleIn 0.5s ease-out;
        }

        #step5 .success-icon svg {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.5s ease-out 0.3s forwards;
        }

        .btn-hover-effect {
            position: relative;
            overflow: hidden;
        }

        .btn-hover-effect::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 0;
            background: #21263a;
            transition: height 0.3s;
            z-index: 0;
        }

        .btn-hover-effect:hover::before {
            height: 100%;
        }

        .btn-hover-effect:hover svg {
            color: white;
        }

        .btn-content {
            position: relative;
            z-index: 1;
        }
    </style>


        <!---------------HERO BANNER---------------------->

<section class="p-hero bg-gray-100 flex flex-col justify-center items-center gap-2 w-full h-[30vh] mt-[75px]">


    <h1 class="text-2xl font-extrabold">Checkout</h1>

    <div class="b-crumb flex gap-1 text-sm text-gray-700">
        <a href="#" class="hover:text-black transition-colors">Home</a>
        <span class="text-gray-500">/</span>
        <a href="#" class="hover:text-black transition-colors">Book</a>
        <span class="text-gray-500">/</span>
        <a href="#" class="hover:text-black transition-colors">Product Details</a>
    </div>
</section>

   
<div class="max-w-7xl mx-auto px-5 md:px-20 lg:px-24 py-5 font-sans mt-[60px]">
    <!-- Progress Stepper -->
    <div class="mb-10">
        <a class="inline-flex items-center gap-2 text-gray-900 font-medium mb-8 cursor-pointer hover:text-orange-600 transition-colors" onclick="goBack()">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z"></path>
            </svg>
            Back
        </a>

        <div class="flex justify-between relative">
            <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-300 z-0"></div>
            
            <div class="step flex flex-col items-center relative z-10 flex-1 active">
                <div class="step-number w-8 h-8 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center font-semibold text-gray-400 transition-all">01</div>
                <div class="step-label mt-2 text-sm text-gray-400 text-center font-medium">Shipping Information</div>
            </div>
            <div class="step flex flex-col items-center relative z-10 flex-1">
                <div class="step-number w-8 h-8 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center font-semibold text-gray-400 transition-all">02</div>
                <div class="step-label mt-2 text-sm text-gray-400 text-center font-medium">Shipping Method</div>
            </div>
            <div class="step flex flex-col items-center relative z-10 flex-1">
                <div class="step-number w-8 h-8 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center font-semibold text-gray-400 transition-all">03</div>
                <div class="step-label mt-2 text-sm text-gray-400 text-center font-medium">Order Details</div>
            </div>
            <div class="step flex flex-col items-center relative z-10 flex-1">
                <div class="step-number w-8 h-8 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center font-semibold text-gray-400 transition-all">04</div>
                <div class="step-label mt-2 text-sm text-gray-400 text-center font-medium">Payment</div>
            </div>
            <div class="step flex flex-col items-center relative z-10 flex-1">
                <div class="step-number w-8 h-8 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center font-semibold text-gray-400 transition-all">05</div>
                <div class="step-label mt-2 text-sm text-gray-400 text-center font-medium">Completed</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="checkout-content">
        <div class="checkout-main">
            <!-- Step 1: Shipping Information -->
            @include('shops.checkout.step-1-shipping')

            <!-- Step 2: Shipping Method -->
            @include('shops.checkout.step-2-method')

            <!-- Step 3: Order Details -->
            @include('shops.checkout.step-3-details')

            
            <!-- Step 4: Payment -->
            <div id="step4" class="step-content hidden">
                <h2 class="section-title">Payment Method</h2>
                
                <div class="payment-section">
                    <p class="section-description">Select your preferred payment method</p>
                    
                    <div class="payment-options">
                        <!-- Stripe Payment Option -->
                        <label class="payment-option active">
                            <div class="payment-option-content">
                                <input type="radio" name="payment_method" value="stripe" checked>
                                <div class="payment-option-details">
                                    <h4>Card, Bank Transfer & More</h4>
                                    <p>Pay with card, bank transfer, or other local payment methods</p>
                                </div>
                                <svg class="payment-icon" viewBox="0 0 48 32" fill="none">
                                    <rect width="48" height="32" rx="4" fill="#635BFF"/>
                                    <path d="M21 10h-6v12h6V10zm-2 6c0-2.2-1.8-4-4-4s-4 1.8-4 4 1.8 4 4 4 4-1.8 4-4z" fill="white"/>
                                </svg>
                            </div>
                        </label>

                        <!-- PayPal Payment Option - DISABLED/COMING SOON -->
                        <div class="payment-option disabled">
                            <div class="payment-option-content">
                                <input type="radio" name="payment_method" value="paypal" disabled>
                                <div class="payment-option-details">
                                    <div class="payment-option-header">
                                        <h4>PayPal</h4>
                                        <span class="badge badge-coming-soon">COMING SOON</span>
                                    </div>
                                    <p>Fast and secure PayPal checkout</p>
                                </div>
                                <svg class="payment-icon" viewBox="0 0 48 32" fill="none">
                                    <path d="M20 8h-8c-.5 0-1 .4-1.1.9L8 24h3l1-6h3c4 0 6-2 6-5s-1-5-5-5zm1 5c0 1-.8 2-2 2h-2l1-4h2c1 0 1 .4 1 2z" fill="#003087"/>
                                    <path d="M32 8h-8c-.5 0-1 .4-1.1.9L20 24h3l1-6h3c4 0 6-2 6-5s-1-5-5-5zm1 5c0 1-.8 2-2 2h-2l1-4h2c1 0 1 .4 1 2z" fill="#0070BA"/>
                                </svg>
                            </div>
                        </div>

                        <!-- ========== UNCOMMENT THIS WHEN PAYPAL IS READY ========== -->
                        <!-- 
                        <label class="payment-option border-2 border-gray-300 rounded p-5 cursor-pointer transition-all hover:border-orange-600">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="paypal" class="w-5 h-5 text-orange-600 focus:ring-orange-600">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-900 mb-1">PayPal</h4>
                                    <p class="text-sm text-gray-600">Fast and secure PayPal checkout</p>
                                </div>
                                <svg class="w-12 h-8" viewBox="0 0 48 32" fill="none">
                                    <path d="M20 8h-8c-.5 0-1 .4-1.1.9L8 24h3l1-6h3c4 0 6-2 6-5s-1-5-5-5zm1 5c0 1-.8 2-2 2h-2l1-4h2c1 0 1 .4 1 2z" fill="#003087"/>
                                    <path d="M32 8h-8c-.5 0-1 .4-1.1.9L20 24h3l1-6h3c4 0 6-2 6-5s-1-5-5-5zm1 5c0 1-.8 2-2 2h-2l1-4h2c1 0 1 .4 1 2z" fill="#0070BA"/>
                                </svg>
                            </div>
                        </label>
                        -->
                        <!-- ========== END OF COMMENTED SECTION ========== -->
                    </div>

                    <!-- Stripe Payment Element Container -->
                    <div id="stripePaymentSection" class="payment-element-container">
                        <div class="payment-element-wrapper">
                            <div id="payment-element">
                                <!-- Stripe Payment Element will be mounted here -->
                                <div class="loading-state">
                                    <div class="spinner"></div>
                                    <p>Loading payment options...</p>
                                </div>
                            </div>
                            <div id="payment-errors" class="error-message hidden"></div>
                        </div>
                        
                        <!-- Supported Payment Methods Info -->
                        <div class="payment-info">
                            <p class="payment-info-title">✓ Supported Payment Methods (UK - GBP):</p>
                            <div class="payment-info-list">
                                <div>• Credit Cards (Visa, Mastercard, Amex)</div>
                                <div>• Debit Cards (Visa Debit, Mastercard Debit)</div>
                                <div>• Apple Pay & Google Pay</div>
                                <div>• Bank Transfer (BACS Direct Debit)</div>
                            </div>
                            <p class="payment-info-note">💳 All payments are processed securely through Stripe</p>
                        </div>
                    </div>

                    <!-- PayPal Button Container -->
                    <div id="paypalButtonSection" class="mb-6 hidden">
                        <div id="paypal-button-container"></div>
                    </div>
                </div>

                <!-- Pay Now Button (for Stripe) -->
                <button type="button" id="payBtn" class="btn btn-primary btn-full" onclick="processPayment()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.0049 6.99979H21.0049C21.5572 6.99979 22.0049 7.4475 22.0049 7.99979V19.9998C22.0049 20.5521 21.5572 20.9998 21.0049 20.9998H3.00488C2.4526 20.9998 2.00488 20.5521 2.00488 19.9998V3.99979C2.00488 3.4475 2.4526 2.99979 3.00488 2.99979H18.0049V6.99979ZM4.00488 8.99979V18.9998H20.0049V8.99979H4.00488ZM4.00488 4.99979V6.99979H16.0049V4.99979H4.00488ZM15.0049 12.9998H18.0049V14.9998H15.0049V12.9998Z"></path>
                    </svg>
                    <span id="payBtnText">Pay Now</span>
                    <svg id="paySpinner" class="spinner hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                
                <!-- Security Badge -->
                <div class="security-badge">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Your payment information is secure and encrypted</span>
                </div>
            </div>

            <!-- Step 5: Completed -->
            @include('shops.checkout.step-5-completed')
        </div>

        <!-- Right Section - Order Summary -->
        @include('shops.checkout.order-summary')
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=GBP"></script>

@include('shops.checkout.checkout-script')


@endsection