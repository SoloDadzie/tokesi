<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageDesignMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_new_design_hero_copy_and_cta(): void
    {
        Product::create([
            'title' => 'Migration Test Book',
            'slug' => 'migration-test-book',
            'sku' => 'MIGRATION-TEST-001',
            'price' => 9.99,
            'type' => 'physical',
            'inventory_qty' => 10,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Crafting Stories', false);
        $response->assertSee('EXPLORE THE BOOKS', false);
        $response->assertSee('The Collection', false);
    }

    public function test_homepage_uses_reference_design_class_names(): void
    {
        Product::create([
            'title' => 'Migration Test Book 2',
            'slug' => 'migration-test-book-2',
            'sku' => 'MIGRATION-TEST-002',
            'price' => 12.49,
            'type' => 'physical',
            'inventory_qty' => 5,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('class="hero-title"', false);
        $response->assertSee('class="books-grid"', false);
        $response->assertSee('class="testimonial"', false);
    }
}
