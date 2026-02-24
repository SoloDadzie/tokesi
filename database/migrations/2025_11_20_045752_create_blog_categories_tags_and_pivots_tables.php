<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categories table
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tags table
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pivot table for article-category relationship
        Schema::create('blog_article_blog_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // SHORT CUSTOM INDEX NAME
            $table->unique(['blog_article_id', 'blog_category_id'], 'article_category_unq');
        });

        // Pivot table for article-tag relationship
        Schema::create('blog_article_blog_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // SHORT CUSTOM INDEX NAME
            $table->unique(['blog_article_id', 'blog_tag_id'], 'article_tag_unq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_article_blog_tag');
        Schema::dropIfExists('blog_article_blog_category');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
    }
};
