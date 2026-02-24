<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\Testimonial;
use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $fiction = Category::firstOrCreate(['slug' => 'fiction'], ['name' => 'Fiction']);
        $adventure = Category::firstOrCreate(['slug' => 'adventure'], ['name' => 'Adventure']);
        $educational = Category::firstOrCreate(['slug' => 'educational'], ['name' => 'Educational']);

        // Create Tags
        $bestseller = Tag::firstOrCreate(['slug' => 'bestseller'], ['name' => 'Bestseller']);
        $newRelease = Tag::firstOrCreate(['slug' => 'new-release'], ['name' => 'New Release']);

        // Create Products (Books)
        $product1 = Product::firstOrCreate(
            ['sku' => 'BOOK-SPG-001'],
            [
            'title' => "Sarah's Perfect Gift",
            'slug' => 'sarahs-perfect-gift',
            'short_description' => 'A heartwarming story about finding the perfect gift for someone special.',
            'long_description' => '<p>A heartwarming story about finding the perfect gift for someone special. Follow Sarah on her journey as she discovers that the best gifts come from the heart.</p>',
            'price' => 12.99,
            'inventory_qty' => 50,
            'is_active' => true,
            'is_featured' => true,
            'type' => 'physical',
            ]
        );
        $product1->categories()->syncWithoutDetaching([$fiction->id, $adventure->id]);
        $product1->tags()->syncWithoutDetaching([$bestseller->id]);

        $product2 = Product::firstOrCreate(
            ['sku' => 'BOOK-TMG-002'],
            [
            'title' => 'The Magic Garden',
            'slug' => 'the-magic-garden',
            'short_description' => 'Join Emma on an adventure through a magical garden where flowers talk and trees dance.',
            'long_description' => '<p>Join Emma on an adventure through a magical garden where flowers talk and trees dance. A delightful tale of imagination and wonder.</p>',
            'price' => 14.99,
            'inventory_qty' => 35,
            'is_active' => true,
            'is_featured' => true,
            'type' => 'physical',
            ]
        );
        $product2->categories()->syncWithoutDetaching([$fiction->id, $adventure->id]);
        $product2->tags()->syncWithoutDetaching([$newRelease->id]);

        $product3 = Product::firstOrCreate(
            ['sku' => 'BOOK-LWL-003'],
            [
            'title' => 'Learning with Leo',
            'slug' => 'learning-with-leo',
            'short_description' => 'An educational journey with Leo the Lion as he learns about numbers, colors, and shapes.',
            'long_description' => '<p>An educational journey with Leo the Lion as he learns about numbers, colors, and shapes. Perfect for early learners!</p>',
            'price' => 11.99,
            'inventory_qty' => 60,
            'is_active' => true,
            'is_featured' => false,
            'type' => 'physical',
        ]);
        $product3->categories()->attach([$educational->id]);

        // Create Blog Categories
        $writingTips = BlogCategory::firstOrCreate(['slug' => 'writing-tips'], ['name' => 'Writing Tips']);
        $bookNews = BlogCategory::firstOrCreate(['slug' => 'book-news'], ['name' => 'Book News']);

        // Create Blog Articles
        BlogArticle::firstOrCreate(
            ['slug' => 'how-to-spark-creativity-in-young-readers'],
            [
            'title' => 'How to Spark Creativity in Young Readers',
            'slug' => 'how-to-spark-creativity-in-young-readers',
            'short_description' => 'Discover effective ways to encourage creativity and imagination in children through reading.',
            'content' => '<p>Reading is one of the most powerful tools for developing creativity in children. Here are some tips to make reading more engaging...</p>',
            'type' => 'blog',
            'status' => 'published',
            'published_at' => now(),
            'featured_image' => '/imgs/blog-creativity.jpg',
            ]
        );

        BlogArticle::firstOrCreate(
            ['slug' => 'new-book-release-the-magic-garden'],
            [
            'title' => 'New Book Release: The Magic Garden',
            'slug' => 'new-book-release-the-magic-garden',
            'short_description' => 'Excited to announce my latest children\'s book, The Magic Garden, now available!',
            'content' => '<p>I\'m thrilled to share my newest adventure with you all. The Magic Garden tells the story of Emma...</p>',
            'type' => 'blog',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'featured_image' => '/imgs/blog-magic-garden.jpg',
            ]
        );

        // Create Testimonials
        Testimonial::firstOrCreate(
            ['name' => 'Sarah Johnson', 'location' => 'Manchester, UK'],
            [
            'name' => 'Sarah Johnson',
            'location' => 'Manchester, UK',
            'text' => 'My daughter absolutely loves Tokesi\'s books! They\'ve sparked her imagination and love for reading.',
            'order' => 1,
            'is_active' => true,
        ]);

        Testimonial::create([
            'name' => 'Michael Brown',
            'location' => 'London, UK',
            'text' => 'These books are perfect for my classroom. The stories are engaging and teach valuable lessons.',
            'order' => 2,
            'is_active' => true,
        ]);

        Testimonial::create([
            'name' => 'Emily Davis',
            'location' => 'Birmingham, UK',
            'text' => 'Tokesi Akinola\'s books are always in high demand at our library. Kids can\'t get enough!',
            'order' => 3,
            'is_active' => true,
            ]
        );

        // Create Shipping Methods
        ShippingMethod::firstOrCreate(
            ['name' => 'Standard Shipping'],
            [
            'name' => 'Standard Shipping',
            'description' => 'Delivery in 5-7 business days',
            'price' => 4.99,
            'estimated_days' => '5-7',
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'name' => 'Express Shipping',
            'description' => 'Delivery in 2-3 business days',
            'price' => 9.99,
            'estimated_days' => '2-3',
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'name' => 'Next Day Delivery',
            'description' => 'Delivery next business day',
            'price' => 14.99,
            'estimated_days' => '1',
            'is_active' => true,
        ]);

        $this->command->info('Sample data seeded successfully!');
    }
}
