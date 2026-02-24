<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('delivery_time')->nullable(); // e.g., "2-3 Days"
            $table->string('country')->nullable(); // NULL means all countries
            $table->string('state')->nullable(); // NULL means all states
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['country', 'state', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};