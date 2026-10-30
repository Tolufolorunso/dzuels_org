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
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (! Schema::hasColumn('events', 'gallery_album_id')) {
                    $table->foreignId('gallery_album_id')->nullable()->constrained('gallery_albums')->nullOnDelete();
                }
                if (! Schema::hasColumn('events', 'gallery_photos')) {
                    $table->json('gallery_photos')->nullable();
                }
            });
        }

        if (Schema::hasTable('programmes')) {
            Schema::table('programmes', function (Blueprint $table) {
                if (! Schema::hasColumn('programmes', 'gallery_photos')) {
                    $table->json('gallery_photos')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'gallery_album_id')) {
                    $table->dropForeign(['gallery_album_id']);
                    $table->dropColumn('gallery_album_id');
                }
                if (Schema::hasColumn('events', 'gallery_photos')) {
                    $table->dropColumn('gallery_photos');
                }
            });
        }

        if (Schema::hasTable('programmes')) {
            Schema::table('programmes', function (Blueprint $table) {
                if (Schema::hasColumn('programmes', 'gallery_photos')) {
                    $table->dropColumn('gallery_photos');
                }
            });
        }
    }
};
