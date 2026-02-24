<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopDesignMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_page_uses_reference_design_hero_and_layout_sections(): void
    {
        Product::create([
            'title' => 'Shop Migration Book',
            'slug' => 'shop-migration-book',
            'sku' => 'SHOP-MIGRATION-001',
            'price' => 11.50,
            'type' => 'physical',
            'inventory_qty' => 12,
            'is_active' => true,
        ]);

        $response = $this->get(route('shop.index'));

        $response->assertOk();
        $response->assertSee('Discover faith-based children\'s books that inspire and nurture young hearts', false);
        $response->assertSee('class="page-hero-title"', false);
        $response->assertSee('class="shop-section"', false);
    }

    public function test_shop_page_renders_reference_products_grid_and_amazon_banner_copy(): void
    {
        Product::create([
            'title' => 'Shop Migration Book 2',
            'slug' => 'shop-migration-book-2',
            'sku' => 'SHOP-MIGRATION-002',
            'price' => 8.99,
            'type' => 'physical',
            'inventory_qty' => 8,
            'is_active' => true,
        ]);

        $response = $this->get(route('shop.index'));

        $response->assertOk();
        $response->assertSee('class="products-grid"', false);
        $response->assertSee('Also Available on Amazon', false);
    }
}
