<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->enum('type', ['physical', 'pdf', 'both'])->default('physical');
            $table->integer('inventory_qty')->default(0);
            $table->string('pdf_file_path')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->decimal('coupon_percentage', 5, 2)->nullable();
            $table->integer('coupon_usage_limit')->nullable();
            $table->integer('coupon_usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};