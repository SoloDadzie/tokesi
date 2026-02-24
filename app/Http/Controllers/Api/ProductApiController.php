<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductApiController extends Controller
{
    public function show($id)
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