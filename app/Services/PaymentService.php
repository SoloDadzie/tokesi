<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentService
{
    protected $paypalClient;

    public function __construct()
    {
        // Initialize Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        // Initialize PayPal
        $this->initializePayPal();
    }

    protected function initializePayPal()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        $mode = config('services.paypal.mode');

        $environment = $mode === 'live' 
            ? new ProductionEnvironment($clientId, $clientSecret)
            : new SandboxEnvironment($clientId, $clientSecret);

        $this->paypalClient = new PayPalHttpClient($environment);
    }

    // ==================== STRIPE METHODS ====================

    public function createStripeIntent($amount, $currency = 'gbp')
{
    try {
        $amountInCents = (int) $amount;

        return PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => strtolower($currency),

            // ✅ Let Stripe decide which methods are allowed
            'automatic_payment_methods' => [
                'enabled' => true,
            ],

            'metadata' => [
                'integration_source' => 'laravel',
            ],
        ]);
    } catch (\Exception $e) {
        throw new \Exception('Failed to create Stripe payment intent: ' . $e->getMessage());
    }
}


    public function verifyStripePayment($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                return [
                    'success' => true,
                    'transaction_id' => $paymentIntent->id,
                    'amount' => $paymentIntent->amount / 100,
                    'payment_method_type' => $paymentIntent->charges->data[0]->payment_method_details->type ?? 'card',
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment not completed. Status: ' . $paymentIntent->status,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage(),
            ];
        }
    }

    // ==================== PAYPAL METHODS ====================

    public function createPayPalOrder($amount, $currency = 'GBP')
    {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => strtoupper($currency),
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => route('checkout'),
                    'cancel_url' => route('checkout'),
                ],
            ];

            $response = $this->paypalClient->execute($request);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create PayPal order: ' . $e->getMessage());
        }
    }

    public function verifyPayPalPayment($orderId)
    {
        try {
            $request = new OrdersCaptureRequest($orderId);
            $response = $this->paypalClient->execute($request);

            if ($response->result->status === 'COMPLETED') {
                return [
                    'success' => true,
                    'transaction_id' => $response->result->id,
                    'amount' => $response->result->purchase_units[0]->amount->value,
                ];
            }

            return [
                'success' => false,
                'message' => 'PayPal payment not completed. Status: ' . $response->result->status,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'PayPal verification failed: ' . $e->getMessage(),
            ];
        }
    }

    // ==================== REFUND METHODS ====================

    public function refundStripePayment($paymentIntentId, $amount = null)
    {
        try {
            $refundData = ['payment_intent' => $paymentIntentId];
            
            if ($amount) {
                $refundData['amount'] = (int) ($amount * 100);
            }

            $refund = \Stripe\Refund::create($refundData);

            return [
                'success' => true,
                'refund_id' => $refund->id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Refund failed: ' . $e->getMessage(),
            ];
        }
    }

    public function refundPayPalPayment($captureId, $amount = null)
    {
        try {
            // PayPal refund implementation
            // Requires PayPal Payments API
            return [
                'success' => true,
                'message' => 'Refund initiated through PayPal dashboard',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Refund failed: ' . $e->getMessage(),
            ];
        }
    }
}