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
        if (Schema::hasTable('gallery_photos') && ! Schema::hasColumn('gallery_photos', 'is_public')) {
            Schema::table('gallery_photos', function (Blueprint $table) {
                $table->boolean('is_public')->default(true)->index()->after('year');
            });
        }

        if (Schema::hasTable('media_items') && ! Schema::hasColumn('media_items', 'is_public')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->boolean('is_public')->default(true)->index()->after('year');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('gallery_photos') && Schema::hasColumn('gallery_photos', 'is_public')) {
            Schema::table('gallery_photos', function (Blueprint $table) {
                $table->dropIndex(['is_public']);
                $table->dropColumn('is_public');
            });
        }

        if (Schema::hasTable('media_items') && Schema::hasColumn('media_items', 'is_public')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->dropIndex(['is_public']);
                $table->dropColumn('is_public');
            });
        }
    }
};

