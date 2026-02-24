<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PaymentService;
use App\Mail\OrderConfirmation;
use App\Mail\AdminOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Refund;

class OrderController extends Controller
{
    protected $cartService;
    protected $paymentService;

    public function __construct(CartService $cartService, PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->paymentService = $paymentService;
    }

    public function checkout()
    {
        $cartItems = $this->cartService->getItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty');
        }

        return view('shops.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $this->cartService->getSubtotal(),
            'total' => $this->cartService->getTotalWithCoupon(),
        ]);
    }

    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:stripe,paypal',
            'amount' => 'required|integer|min:50',
            'currency' => 'required|string|in:gbp,usd,eur',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zipcode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'shipping_method' => 'required',
            'shipping_cost' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $cartItems = $this->cartService->getItems();
            
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty',
                ], 400);
            }

            // Amount is already in cents from frontend
            $amount = $request->amount;
            $currency = strtolower($request->currency);

            if ($request->payment_method === 'stripe') {
                // Create Stripe Payment Intent
                $paymentIntent = $this->paymentService->createStripeIntent($amount, $currency);
                
                return response()->json([
                    'success' => true,
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                ]);
                
            } elseif ($request->payment_method === 'paypal') {
                // Convert cents to dollars for PayPal
                $amountInDollars = $amount / 100;
                $paypalOrder = $this->paymentService->createPayPalOrder($amountInDollars, strtoupper($currency));
                
                return response()->json([
                    'success' => true,
                    'order_id' => $paypalOrder->result->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment Intent Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function processOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:stripe,paypal',
            'payment_intent_id' => 'required_if:payment_method,stripe',
            'paypal_order_id' => 'required_if:payment_method,paypal',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zipcode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'shipping_method' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $cartItems = $this->cartService->getItems();
            
            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = $this->cartService->getSubtotal();
            $shippingCost = floatval($request->shipping_cost);
            $discountAmount = 0;
            $couponCode = null;
            
            $appliedCoupon = $this->cartService->getAppliedCoupon();
            if ($appliedCoupon) {
                $discountAmount = $subtotal * ($appliedCoupon->percentage / 100);
                $couponCode = $appliedCoupon->code;
            }
            
            $total = ($subtotal - $discountAmount) + $shippingCost;

            // Verify payment based on method
            if ($request->payment_method === 'stripe') {
                $paymentVerified = $this->paymentService->verifyStripePayment($request->payment_intent_id);
            } else {
                $paymentVerified = $this->paymentService->verifyPayPalPayment($request->paypal_order_id);
            }

            if (!$paymentVerified['success']) {
                throw new \Exception($paymentVerified['message']);
            }

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'shipping_address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zipcode' => $request->zipcode,
                'country' => $request->country,
                'message' => $request->message,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shippingCost,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'payment_intent_id' => $request->payment_intent_id ?? null,
                'transaction_id' => $paymentVerified['transaction_id'] ?? null,
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'product_sku' => $product->sku,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->getSubtotal(),
                    'product_snapshot' => json_encode([
                        'title' => $product->title,
                        'sku' => $product->sku,
                        'image' => $product->primaryImage?->image_path,
                    ]),
                ]);

                // Update inventory for physical products
                if (in_array($product->type, ['physical', 'both'])) {
                    $product->decrement('inventory_qty', $cartItem->quantity);
                }
            }

            // Send confirmation emails
            try {
                Mail::to($order->email)->send(new OrderConfirmation($order));
                Mail::to(config('mail.admin_email', env('ADMIN_EMAIL')))->send(new AdminOrderNotification($order));
            } catch (\Exception $e) {
                Log::error('Email sending failed: ' . $e->getMessage());
                // Don't fail the order if email fails
            }

            // Clear cart
            $this->cartService->clear();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_number' => $order->order_number,
                'order_id' => $order->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order processing failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Order processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function orderConfirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();

        return view('shops.order-confirmation', compact('order'));
    }

    public function trackOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'order_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email and order number.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $order = Order::where('email', $request->email)
                ->where('order_number', $request->order_number)
                ->with('items')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'No order found with this email and order number. Please check your details and try again.',
                ], 404);
            }

            // Format order data for frontend
            $orderData = [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'shipped' => in_array($order->status, ['shipped', 'delivered']),
                'shipping_company' => $order->shipping_company,
                'tracking_number' => $order->tracking_number,
                'tracking_link' => $order->tracking_link,
                'shipping_method' => $order->shipping_method,
                'shipping_fee' => '£' . number_format($order->shipping_cost, 2),
                'items' => $order->items->map(function ($item) {
                    $snapshot = $item->getProductSnapshot();
                    return [
                        'id' => $item->id,
                        'name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => '£' . number_format($item->subtotal, 2),
                        'image' => $snapshot['image'] ? asset('storage/' . $snapshot['image']) : asset('images/default-product.png'),
                    ];
                }),
                'delivery' => [
                    'firstName' => $order->first_name,
                    'lastName' => $order->last_name,
                    'email' => $order->email,
                    'address' => $order->shipping_address,
                    'country' => $order->country,
                    'state' => $order->state,
                    'city' => $order->city,
                    'zipcode' => $order->zipcode,
                ],
                'steps' => [
                    ['name' => 'Order Processed', 'completed' => true],
                    ['name' => 'Order Shipped', 'completed' => in_array($order->status, ['shipped', 'delivered'])],
                    ['name' => 'Order Delivered', 'completed' => $order->status === 'delivered'],
                ],
            ];

            return response()->json([
                'success' => true,
                'order' => $orderData,
            ]);

        } catch (\Exception $e) {
            Log::error('Track Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while tracking your order. Please try again later.',
            ], 500);
        }
    }

    public function updateDeliveryAddress(Request $request, $orderNumber)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zipcode' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $order = Order::where('order_number', $orderNumber)
                ->where('email', $request->input('original_email', $request->email))
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            if (!$order->canUpdateAddress()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update address for orders that have been shipped or delivered.',
                ], 400);
            }

            $order->update([
                'email' => $request->email,
                'shipping_address' => $request->address,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'zipcode' => $request->zipcode,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Delivery address updated successfully. Check your email for confirmation.',
            ]);

        } catch (\Exception $e) {
            Log::error('Update Address Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancelOrder($orderNumber)
    {
        try {
            $order = Order::where('order_number', $orderNumber)->firstOrFail();

            if (!$order->canBeCancelled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order cannot be cancelled as it has already been shipped or delivered.',
                ], 400);
            }

            // Process refund if payment was successful
            if ($order->payment_status === 'paid' && $order->payment_method === 'stripe') {
                Stripe::setApiKey(config('services.stripe.secret'));

                try {
                    Refund::create([
                        'payment_intent' => $order->payment_intent_id,
                    ]);

                    $order->payment_status = 'refunded';
                } catch (\Exception $e) {
                    Log::error('Refund Error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to process refund: ' . $e->getMessage(),
                    ], 500);
                }
            }

            $order->status = 'cancelled';
            $order->save();

            // Restore inventory
            foreach ($order->items as $item) {
                if ($item->product) {
                    $product = $item->product;
                    if (in_array($product->type, ['physical', 'both'])) {
                        $product->increment('inventory_qty', $item->quantity);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully. Refund will be processed within 5-10 business days.',
            ]);

        } catch (\Exception $e) {
            Log::error('Cancel Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: ' . $e->getMessage(),
            ], 500);
        }
    }
}