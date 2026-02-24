<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;
use App\Services\CartService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty');
        }

        $subtotal = $this->cartService->getSubtotal();
        $appliedCoupon = $this->cartService->getAppliedCoupon();
        $discount = 0;

        if ($appliedCoupon && $appliedCoupon->isValid()) {
            $discount = ($subtotal * $appliedCoupon->percentage) / 100;
        }

        return view('shops.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'appliedCoupon' => $appliedCoupon,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    public function getShippingMethods(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'state' => 'nullable|string',
        ]);

        $shippingMethods = ShippingMethod::active()
            ->forLocation($request->country, $request->state)
            ->get()
            ->map(function ($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'description' => $method->description,
                    'delivery_time' => $method->delivery_time,
                    'price' => floatval($method->price),
                    'formatted_price' => $method->formatted_price,
                ];
            });

        return response()->json([
            'success' => true,
            'shipping_methods' => $shippingMethods,
        ]);
    }

    public function getCartSummary()
    {
        $cartItems = $this->cartService->getItems();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        $items = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->title,
                'sku' => $item->product->sku,
                'price' => floatval($item->price),
                'quantity' => $item->quantity,
                'subtotal' => floatval($item->getSubtotal()),
                'image' => $item->product->primaryImage 
                    ? asset('storage/' . $item->product->primaryImage->image_path) 
                    : null,
            ];
        });

        $subtotal = $this->cartService->getSubtotal();
        $appliedCoupon = $this->cartService->getAppliedCoupon();
        $discount = 0;

        if ($appliedCoupon && $appliedCoupon->isValid()) {
            $discount = ($subtotal * $appliedCoupon->percentage) / 100;
        }

        return response()->json([
            'success' => true,
            'items' => $items,
            'subtotal' => floatval($subtotal),
            'discount' => floatval($discount),
            'coupon_code' => $appliedCoupon?->code,
            'total_before_shipping' => floatval($subtotal - $discount),
        ]);
    }
}