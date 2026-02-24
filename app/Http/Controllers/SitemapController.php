<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BlogArticle;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use SimpleXMLElement;

class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap
     */
    public function index()
    {
        $cacheKey = config('sitemap.cache_key', 'laravel-sitemap.sitemap');

        if (config('sitemap.use_cache') && Cache::has($cacheKey)) {
            return response(Cache::get($cacheKey), 200, [
                'Content-Type' => 'application/xml; charset=utf-8',
            ]);
        }

        $sitemap = $this->generateSitemap();

        if (config('sitemap.use_cache')) {
            Cache::put($cacheKey, $sitemap, config('sitemap.cache_duration', 3600));
        }

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Generate sitemap XML
     */
    private function generateSitemap()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $baseUrl = config('sitemap.url', config('app.url'));

        // Add static pages
        $this->addUrl($xml, '/', 'weekly', 1.0, now());
        $this->addUrl($xml, '/about', 'monthly', 0.8, now());
        $this->addUrl($xml, '/contact', 'monthly', 0.7, now());
        $this->addUrl($xml, '/shop', 'weekly', 0.9, now());
        $this->addUrl($xml, '/blogs', 'weekly', 0.8, now());

        // Add location pages
        $locations = ['wigan', 'manchester'];
        foreach ($locations as $location) {
            $this->addUrl($xml, "/{$location}", 'weekly', 0.85, now());
        }

        // Add featured products
        $products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->select('slug', 'updated_at')
            ->get();

        foreach ($products as $product) {
            $this->addUrl($xml, "/shop/product/{$product->slug}", 'weekly', 0.9, $product->updated_at);
        }

        // Add all active products
        $allProducts = Product::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get();

        foreach ($allProducts as $product) {
            $this->addUrl($xml, "/shop/product/{$product->slug}", 'monthly', 0.7, $product->updated_at);
        }

        // Add published blog articles
        $articles = BlogArticle::where('status', 'published')
            ->select('slug', 'published_at')
            ->get();

        foreach ($articles as $article) {
            $this->addUrl($xml, "/blog/{$article->slug}", 'weekly', 0.8, $article->published_at);
        }

        return $xml->asXML();
    }

    /**
     * Add URL to sitemap
     */
    private function addUrl($xml, $path, $changefreq = 'monthly', $priority = 0.5, $lastmod = null)
    {
        $url = $xml->addChild('url');
        $url->addChild('loc', config('sitemap.url') . $path);

        if ($lastmod) {
            $url->addChild('lastmod', $lastmod->toAtomString());
        }

        $url->addChild('changefreq', $changefreq);
        $url->addChild('priority', $priority);
    }

    /**
     * Clear the sitemap cache
     */
    public function clearCache()
    {
        $cacheKey = config('sitemap.cache_key', 'laravel-sitemap.sitemap');
        Cache::forget($cacheKey);

        return response()->json(['message' => 'Sitemap cache cleared successfully.']);
    }
}
