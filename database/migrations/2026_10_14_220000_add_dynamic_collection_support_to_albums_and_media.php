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
        if (Schema::hasTable('gallery_albums') && ! Schema::hasColumn('gallery_albums', 'is_public')) {
            Schema::table('gallery_albums', function (Blueprint $table) {
                $table->boolean('is_public')->default(true)->after('is_active');
            });
        }

        if (Schema::hasTable('media_items') && ! Schema::hasColumn('media_items', 'gallery_album_id')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->foreignId('gallery_album_id')
                    ->nullable()
                    ->after('source_type')
                    ->constrained('gallery_albums')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('media_items') && Schema::hasColumn('media_items', 'gallery_album_id')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->dropForeign(['gallery_album_id']);
                $table->dropColumn('gallery_album_id');
            });
        }

        if (Schema::hasTable('gallery_albums') && Schema::hasColumn('gallery_albums', 'is_public')) {
            Schema::table('gallery_albums', function (Blueprint $table) {
                $table->dropColumn('is_public');
            });
        }
    }
};
