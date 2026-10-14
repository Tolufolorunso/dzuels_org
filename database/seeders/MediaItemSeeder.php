<?php

namespace Database\Seeders;

use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Backfill from GalleryPhoto records
        if (class_exists(GalleryPhoto::class)) {
            $galleryPhotos = GalleryPhoto::with('album')->get();

            foreach ($galleryPhotos as $photo) {
                $rawPath = $photo->image_path;
                if (blank($rawPath)) {
                    continue;
                }

                $filePath = $rawPath;
                if (! str_starts_with($rawPath, 'front/') && ! str_starts_with($rawPath, 'uploads/')) {
                    $filePath = 'uploads/gallery/'.$rawPath;
                }

                $fullPath = public_path($filePath);
                $fileSize = 0;
                $width = null;
                $height = null;
                $mimeType = 'image/webp';

                if (File::exists($fullPath)) {
                    $fileSize = File::size($fullPath);
                    $imageInfo = @getimagesize($fullPath);
                    if ($imageInfo) {
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                        $mimeType = $imageInfo['mime'] ?? 'image/webp';
                    }
                }

                $title = $photo->caption ?: ($photo->album?->title ?? 'Gallery Photo');
                if ($photo->year && ! str_contains($title, (string) $photo->year)) {
                    $title .= ' ('.$photo->year.')';
                }

                MediaItem::updateOrCreate(
                    ['file_path' => $filePath],
                    [
                        'filename' => basename($filePath),
                        'file_size' => $fileSize,
                        'mime_type' => $mimeType,
                        'width' => $width,
                        'height' => $height,
                        'title' => $title,
                        'caption' => $photo->caption,
                        'alt_text' => $photo->alt_text ?: $photo->caption,
                        'source_type' => 'gallery',
                        'gallery_album_id' => $photo->gallery_album_id,
                        'gallery_photo_id' => $photo->id,
                    ]
                );
            }
        }

        // 2. Backfill existing uploaded media in public/uploads/
        $uploadsDir = public_path('uploads');
        if (File::isDirectory($uploadsDir)) {
            $uploadedFiles = File::allFiles($uploadsDir);
            foreach ($uploadedFiles as $file) {
                $ext = strtolower($file->getExtension());
                if (! in_array($ext, ['webp', 'jpg', 'jpeg', 'png', 'gif', 'svg'])) {
                    continue;
                }

                $relativePath = str_replace('\\', '/', Str::after($file->getPathname(), public_path().DIRECTORY_SEPARATOR));
                $relativePath = ltrim($relativePath, '/');

                if (MediaItem::where('file_path', $relativePath)->exists()) {
                    continue;
                }

                $imageInfo = @getimagesize($file->getPathname());
                $nameWithoutExt = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $title = Str::title(str_replace(['-', '_'], ' ', $nameWithoutExt));

                MediaItem::create([
                    'filename' => $file->getFilename(),
                    'file_path' => $relativePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $imageInfo['mime'] ?? ('image/'.$ext),
                    'width' => $imageInfo[0] ?? null,
                    'height' => $imageInfo[1] ?? null,
                    'title' => $title,
                    'caption' => null,
                    'alt_text' => $title,
                    'source_type' => str_contains($relativePath, 'gallery') ? 'gallery' : 'general',
                    'gallery_photo_id' => null,
                ]);
            }
        }

        // 3. Backfill site assets from public/front/images/
        $frontImagesDir = public_path('front/images');
        if (File::isDirectory($frontImagesDir)) {
            $frontFiles = File::allFiles($frontImagesDir);
            foreach ($frontFiles as $file) {
                $ext = strtolower($file->getExtension());
                if (! in_array($ext, ['webp', 'jpg', 'jpeg', 'png', 'svg'])) {
                    continue;
                }

                $relativePath = str_replace('\\', '/', Str::after($file->getPathname(), public_path().DIRECTORY_SEPARATOR));
                $relativePath = ltrim($relativePath, '/');

                if (MediaItem::where('file_path', $relativePath)->exists()) {
                    continue;
                }

                $imageInfo = @getimagesize($file->getPathname());
                $nameWithoutExt = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $title = Str::title(str_replace(['-', '_'], ' ', $nameWithoutExt));

                MediaItem::create([
                    'filename' => $file->getFilename(),
                    'file_path' => $relativePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $imageInfo['mime'] ?? ('image/'.$ext),
                    'width' => $imageInfo[0] ?? null,
                    'height' => $imageInfo[1] ?? null,
                    'title' => $title,
                    'caption' => null,
                    'alt_text' => $title,
                    'source_type' => 'cms',
                    'gallery_photo_id' => null,
                ]);
            }
        }
    }
}
