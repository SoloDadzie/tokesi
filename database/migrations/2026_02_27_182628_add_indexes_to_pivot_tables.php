<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to category_product pivot table
        Schema::table('category_product', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('category_id');
            $table->unique(['product_id', 'category_id']);
        });

        // Add indexes to product_tag pivot table
        Schema::table('product_tag', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('tag_id');
            $table->unique(['product_id', 'tag_id']);
        });

        // Add indexes to product_images table
        Schema::table('product_images', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_product', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['category_id']);
            $table->dropUnique(['product_id', 'category_id']);
        });

        Schema::table('product_tag', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['tag_id']);
            $table->dropUnique(['product_id', 'tag_id']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['is_primary']);
        });
    }
};
