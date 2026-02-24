<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['blog', 'event'])->default('blog');
            $table->text('short_description')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('author_name')->default('Tokesi Akinola');
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Publishing
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);
            
            // Engagement
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'published_at']);
            $table->index('type');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_articles');
    }
};