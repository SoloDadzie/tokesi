<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::active()
            ->ordered()
            ->get()
            ->map(function ($testimonial) {
                return [
                    'initial' => $testimonial->initial,
                    'name' => $testimonial->name,
                    'location' => $testimonial->location,
                    'text' => $testimonial->text,
                ];
            });

        return response()->json($testimonials);
    }
}