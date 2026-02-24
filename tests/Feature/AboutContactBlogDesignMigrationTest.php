<?php

namespace Tests\Feature;

use App\Models\BlogArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutContactBlogDesignMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_uses_reference_design_hero_and_sections(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('class="page-hero-title"', false);
        $response->assertSee('About the Author', false);
        $response->assertSee('class="mission"', false);
    }

    public function test_contact_page_uses_reference_design_hero_and_contact_layout(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('class="page-hero-title"', false);
        $response->assertSee('Contact Information', false);
        $response->assertSee('class="contact-form"', false);
    }

    public function test_blog_index_page_uses_reference_design_layout_sections(): void
    {
        BlogArticle::create([
            'title' => 'Blog Migration Story',
            'slug' => 'blog-migration-story',
            'type' => 'blog',
            'short_description' => 'Blog migration short description.',
            'content' => '<p>Blog migration content body.</p>',
            'author_name' => 'Tokesi Akinola',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        $response->assertSee('class="blog-section"', false);
        $response->assertSee('class="events-section"', false);
    }

    public function test_blog_show_page_uses_reference_detail_layout_structure(): void
    {
        $article = BlogArticle::create([
            'title' => 'Blog Detail Migration Story',
            'slug' => 'blog-detail-migration-story',
            'type' => 'blog',
            'short_description' => 'Blog detail migration short description.',
            'content' => '<p>Blog detail migration content body.</p>',
            'author_name' => 'Tokesi Akinola',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('blog.show', $article->slug));

        $response->assertOk();
        $response->assertSee('blog-detail-title', false);
        $response->assertSee('comments-section', false);
    }
}
