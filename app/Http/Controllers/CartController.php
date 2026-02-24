<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
            'coupon_code' => 'nullable|string',
        ]);

        $result = $this->cartService->addItem(
            $request->product_id,
            $request->quantity ?? 1,
            $request->coupon_code
        );

        return response()->json($result);
    }

    public function get()
    {
        $items = $this->cartService->getItems();
        $cartData = [];

        foreach ($items as $item) {
            $product = $item->product;
            $cartData[] = [
                'id' => $item->id,
                'product_id' => $product->id,
                'name' => $product->title,
                'price' => floatval($item->price),
                'quantity' => $item->quantity,
                'image' => $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : null,
                'subtotal' => floatval($item->getSubtotal()),
            ];
        }

        return response()->json([
            'success' => true,
            'items' => $cartData,
            'count' => $this->cartService->getItemCount(),
            'subtotal' => floatval($this->cartService->getSubtotal()),
            'total' => floatval($this->cartService->getTotalWithCoupon()),
            'applied_coupon' => $this->cartService->getAppliedCoupon(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $result = $this->cartService->updateQuantity($id, $request->quantity);
        return response()->json($result);
    }

    public function remove($id)
    {
        $result = $this->cartService->removeItem($id);
        return response()->json($result);
    }

    public function clear()
    {
        $this->cartService->clear();
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $result = $this->cartService->applyCoupon($request->code);
        
        if ($result['success']) {
            $result['total'] = floatval($this->cartService->getTotalWithCoupon());
        }
        
        return response()->json($result);
    }

    public function removeCoupon()
    {
        $result = $this->cartService->removeCoupon();
        $result['total'] = floatval($this->cartService->getTotal());
        return response()->json($result);
    }
}