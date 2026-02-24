<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            
            // Customer Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            
            // Shipping Address
            $table->text('shipping_address');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('country');
            $table->text('message')->nullable();
            
            // Shipping Method
            $table->string('shipping_method');
            $table->decimal('shipping_cost', 10, 2);
            
            // Order Totals
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('total', 10, 2);
            
            // Payment Information
            $table->enum('payment_method', ['stripe', 'paypal']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_intent_id')->nullable();
            $table->string('transaction_id')->nullable();
            
            // Order Status
            $table->enum('status', [
                'pending',
                'processing',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('pending');
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('email');
            $table->index('status');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('product_sku');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->text('product_snapshot')->nullable(); // JSON snapshot of product at purchase time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};