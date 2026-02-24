<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('coupon_usage_limit');
            $table->timestamp('coupon_valid_from')->nullable()->after('coupon_code');
            $table->timestamp('coupon_valid_until')->nullable()->after('coupon_valid_from');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'coupon_valid_from', 'coupon_valid_until']);
        });
    }
};