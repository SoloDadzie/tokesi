<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['primaryImage', 'categories', 'tags'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        $testimonials = Testimonial::active()
            ->ordered()
            ->get();

        return view('welcome', compact('products', 'testimonials'));
    }

    public function featured()
    {
        $products = Product::with(['primaryImage'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with(['images', 'categories', 'tags'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related products from same category
        $relatedProducts = Product::with(['primaryImage'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->take(4)
            ->get();

        return view('shops.product-details', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $products = Product::with(['primaryImage', 'categories'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhereHas('categories', function ($catQuery) use ($query) {
                      $catQuery->where('name', 'LIKE', "%{$query}%");
                  })
                  ->orWhereHas('tags', function ($tagQuery) use ($query) {
                      $tagQuery->where('name', 'LIKE', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->title,
                    'category' => $product->categories->first()?->name ?? 'Uncategorized',
                    'url' => route('product.show', $product->slug),
                    'image' => $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : null,
                    'price' => $product->getPriceFormatted(),
                ];
            });

        return response()->json($products);
    }

    public function validateCoupon(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $product = Product::findOrFail($id);
        $result = $product->validateProductCoupon($request->code);

        return response()->json($result);
    }

    public function incrementCouponUsage($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->coupon_code) {
            $product->increment('coupon_usage_count');
        }

        return response()->json(['success' => true]);
    }
}