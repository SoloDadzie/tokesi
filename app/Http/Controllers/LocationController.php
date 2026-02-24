<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function show($location)
    {
        // List of supported locations
        $supportedLocations = ['wigan', 'manchester'];

        if (!in_array(strtolower($location), $supportedLocations)) {
            abort(404); // show 404 if location not supported
        }

        // You can pass dynamic content for SEO here
        $title = "Tokesi Akinola – Children’s Book Author in " . ucfirst($location);
        $metaDescription = "Discover children’s books and stories by Tokesi Akinola, the celebrated author in " . ucfirst($location) . ".";

        return view('locations.show', compact('location', 'title', 'metaDescription'));
    }
}
