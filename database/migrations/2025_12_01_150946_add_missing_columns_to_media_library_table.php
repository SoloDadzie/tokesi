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
        Schema::table('media_library', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('media_library', 'width')) {
                $table->integer('width')->nullable()->after('disk');
            }
            
            if (!Schema::hasColumn('media_library', 'height')) {
                $table->integer('height')->nullable()->after('width');
            }
            
            if (!Schema::hasColumn('media_library', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('height');
            }
            
            if (!Schema::hasColumn('media_library', 'size_human')) {
                $table->string('size_human')->nullable()->after('size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_library', function (Blueprint $table) {
            $table->dropColumn(['width', 'height', 'dimensions', 'size_human']);
        });
    }
};