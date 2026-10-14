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
        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('file_path')->index();
            $table->unsignedInteger('file_size')->default(0);
            $table->string('mime_type')->default('image/webp');
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('title')->nullable()->index();
            $table->text('caption')->nullable();
            $table->text('alt_text')->nullable();
            $table->string('source_type', 30)->default('general')->index();
            $table->foreignId('gallery_photo_id')->nullable()->constrained('gallery_photos')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
