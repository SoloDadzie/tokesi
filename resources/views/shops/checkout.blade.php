@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<section class="page-hero">
    <div class="page-hero-content">
        <h1>Checkout</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('shop.index') }}">Shop</a>
            <span>/</span>
            <span>Checkout</span>
        </div>
    </div>
</section>

<div class="checkout-container">
    <!-- Progress Stepper -->
    <div class="checkout-progress">
        <a class="back-link" onclick="goBack()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z"></path>
            </svg>
            Back
        </a>

        <div class="progress-stepper">
            <div class="progress-line"></div>
            
            <div class="step active">
                <div class="step-number">01</div>
                <div class="step-label">Shipping Information</div>
            </div>
            <div class="step">
                <div class="step-number">02</div>
                <div class="step-label">Shipping Method</div>
            </div>
            <div class="step">
                <div class="step-number">03</div>
                <div class="step-label">Order Details</div>
            </div>
            <div class="step">
                <div class="step-number">04</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step">
                <div class="step-number">05</div>
                <div class="step-label">Completed</div>
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
                            <input type="radio" name="payment_method" value="stripe" checked>
                            <div class="payment-option-details">
                                <h4>Card, Bank Transfer & More</h4>
                                <p>Pay with card, bank transfer, or other local payment methods</p>
                            </div>
                            <svg class="payment-icon" viewBox="0 0 48 32" fill="none">
                                <rect width="48" height="32" rx="4" fill="#635BFF"/>
                                <path d="M21 10h-6v12h6V10zm-2 6c0-2.2-1.8-4-4-4s-4 1.8-4 4 1.8 4 4 4 4-1.8 4-4z" fill="white"/>
                            </svg>
                        </label>

                        <!-- PayPal Payment Option - DISABLED/COMING SOON -->
                        <div class="payment-option disabled">
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
                    <div id="paypalButtonSection" class="hidden">
                        <div id="paypal-button-container"></div>
                    </div>
                </div>

                <!-- Pay Now Button (for Stripe) -->
                <button type="button" id="payBtn" class="btn btn-primary" onclick="processPayment()" style="margin-top: var(--spacing-lg);">
                    <span id="payBtnText">Pay Now</span>
                    <span id="paySpinner" class="spinner hidden"></span>
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
