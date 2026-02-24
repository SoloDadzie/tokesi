<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'categories'])
            ->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Price filter
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Category filter
        if ($request->filled('categories')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

        // Featured filter
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // New arrivals filter
        if ($request->boolean('new_arrival')) {
            $query->where('is_new_arrival', true);
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::all();
        $maxPrice = Product::where('is_active', true)->max('price') ?? 1000;

        return view('shops.index', compact('products', 'categories', 'maxPrice'));
    }

    public function quickView($id)
    {
        $product = Product::with(['images', 'categories'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'price' => $product->price,
            'compare_at_price' => $product->compare_at_price,
            'price_formatted' => $product->getPriceFormatted(),
            'compare_price_formatted' => $product->getComparePriceFormatted(),
            'short_description' => $product->short_description,
            'type' => $product->type,
            'inventory_qty' => $product->inventory_qty,
            'url' => route('product.show', $product->slug),
            'images' => $product->images->map(fn($img) => [
                'url' => asset('storage/' . $img->image_path),
                'is_primary' => $img->is_primary
            ]),
            'categories' => $product->categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug
            ])
        ]);
    }
}