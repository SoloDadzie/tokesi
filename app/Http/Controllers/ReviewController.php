<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'review_text' => 'required|string|min:10|max:1000',
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_approved' => false, // Requires admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted and is awaiting approval.',
            'review' => [
                'id' => $review->id,
                'name' => $review->full_name,
                'rating' => $review->rating,
                'text' => $review->review_text,
                'date' => $review->created_at->format('Y-m-d'),
            ],
        ]);
    }

    public function getProductReviews($productId)
    {
        $product = Product::findOrFail($productId);
        
        $reviews = $product->approvedReviews()
            ->latest()
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'name' => $review->full_name,
                    'rating' => (float) $review->rating,
                    'text' => $review->review_text,
                    'date' => $review->created_at->format('Y-m-d'),
                ];
            });

        $averageRating = $product->getAverageRating();
        $totalReviews = $product->getTotalReviews();
        $breakdown = $product->getRatingBreakdown();

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
            'rating_breakdown' => $breakdown,
        ]);
    }
}