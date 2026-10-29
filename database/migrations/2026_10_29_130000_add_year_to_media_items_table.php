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
        if (Schema::hasTable('media_items') && ! Schema::hasColumn('media_items', 'year')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->unsignedSmallInteger('year')->nullable()->after('source_type')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('media_items') && Schema::hasColumn('media_items', 'year')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->dropIndex(['year']);
                $table->dropColumn('year');
            });
        }
    }
};
