<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailsDesignMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_details_page_uses_reference_layout_sections(): void
    {
        $product = Product::create([
            'title' => 'Product Details Migration Book',
            'slug' => 'product-details-migration-book',
            'sku' => 'PRODUCT-MIGRATION-001',
            'price' => 13.99,
            'type' => 'physical',
            'inventory_qty' => 9,
            'short_description' => 'A migration test product description.',
            'is_active' => true,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertOk();
        $response->assertSee('class="product-detail-title"', false);
        $response->assertSee('class="product-tabs-section"', false);
    }

    public function test_product_details_page_shows_reference_buy_on_amazon_cta(): void
    {
        $product = Product::create([
            'title' => 'Product Details Migration Book 2',
            'slug' => 'product-details-migration-book-2',
            'sku' => 'PRODUCT-MIGRATION-002',
            'price' => 7.49,
            'type' => 'physical',
            'inventory_qty' => 3,
            'short_description' => 'Another migration test product description.',
            'is_active' => true,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertOk();
        $response->assertSee('Buy on Amazon', false);
        $response->assertSee('buy-amazon-btn', false);
    }
}
