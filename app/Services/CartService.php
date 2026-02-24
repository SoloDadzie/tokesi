<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected function getSessionId()
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', uniqid('cart_', true));
        }
        return Session::get('cart_session_id');
    }

    public function addItem($productId, $quantity = 1, $couponCode = null)
    {
        $product = Product::findOrFail($productId);
        
        // Check stock for physical products
        if (in_array($product->type, ['physical', 'both'])) {
            if (!$product->hasStock($quantity)) {
                return ['success' => false, 'message' => 'Not enough stock available'];
            }
        }

        // Determine the price to use
        $price = $product->price;
        $appliedDiscount = null;

        // Check if product coupon code is provided and valid
        if ($couponCode && $product->coupon_code) {
            $validation = $product->validateProductCoupon($couponCode);
            if ($validation['valid']) {
                $price = $validation['discounted_price'];
                $appliedDiscount = [
                    'type' => 'product',
                    'code' => $couponCode,
                    'percentage' => $validation['discount_percentage'],
                ];
            }
        } else if ($product->hasCoupon() && !$product->coupon_code) {
            // Auto-apply product discount if no code required
            $price = $product->getPriceAfterCoupon();
            $appliedDiscount = [
                'type' => 'auto',
                'percentage' => $product->coupon_percentage,
            ];
        }

        $sessionId = $this->getSessionId();
        
        // Check if item already in cart
        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $quantity;
            
            if (in_array($product->type, ['physical', 'both']) && !$product->hasStock($newQuantity)) {
                return ['success' => false, 'message' => 'Not enough stock available'];
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->price = $price; // Update price in case coupon was applied
            if ($appliedDiscount) {
                $cartItem->applied_discount = json_encode($appliedDiscount);
            }
            $cartItem->save();
        } else {
            // Create new cart item
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'applied_discount' => $appliedDiscount ? json_encode($appliedDiscount) : null,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $this->getItemCount(),
            'discount_applied' => $appliedDiscount !== null,
        ];
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::where('id', $cartItemId)
            ->where('session_id', $this->getSessionId())
            ->firstOrFail();

        $product = $cartItem->product;
        
        if ($quantity <= 0) {
            return $this->removeItem($cartItemId);
        }

        if (in_array($product->type, ['physical', 'both']) && !$product->hasStock($quantity)) {
            return ['success' => false, 'message' => 'Not enough stock available'];
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return [
            'success' => true,
            'cart_count' => $this->getItemCount(),
            'cart_total' => $this->getTotal(),
        ];
    }

    public function removeItem($cartItemId)
    {
        CartItem::where('id', $cartItemId)
            ->where('session_id', $this->getSessionId())
            ->delete();

        return [
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $this->getItemCount(),
            'cart_total' => $this->getTotal(),
        ];
    }

    public function getItems()
    {
        return CartItem::with(['product.primaryImage'])
            ->where('session_id', $this->getSessionId())
            ->get();
    }

    public function getItemCount()
    {
        return CartItem::where('session_id', $this->getSessionId())
            ->sum('quantity');
    }

    public function getSubtotal()
    {
        $items = $this->getItems();
        return $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getTotal()
    {
        return $this->getSubtotal(); // Can add shipping/tax later
    }

    public function clear()
    {
        CartItem::where('session_id', $this->getSessionId())->delete();
    }

    public function applyCoupon($couponCode)
    {
        $coupon = \App\Models\Coupon::where('code', strtoupper($couponCode))->first();
        
        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code'];
        }

        if (!$coupon->isValid()) {
            return ['success' => false, 'message' => 'Coupon is not valid or has expired'];
        }

        Session::put('applied_coupon', $coupon->code);
        
        return [
            'success' => true,
            'message' => 'Coupon applied successfully',
            'discount' => $coupon->percentage,
        ];
    }

    public function removeCoupon()
    {
        Session::forget('applied_coupon');
        return ['success' => true, 'message' => 'Coupon removed'];
    }

    public function getAppliedCoupon()
    {
        if (Session::has('applied_coupon')) {
            return \App\Models\Coupon::where('code', Session::get('applied_coupon'))->first();
        }
        return null;
    }

    public function getTotalWithCoupon()
    {
        $subtotal = $this->getSubtotal();
        $coupon = $this->getAppliedCoupon();
        
        if ($coupon && $coupon->isValid()) {
            return $coupon->apply($subtotal);
        }
        
        return $subtotal;
    }
}