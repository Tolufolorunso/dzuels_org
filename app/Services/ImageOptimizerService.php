<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class ImageOptimizerService
{
    /**
     * Optimize an uploaded image file, resize if needed, convert to WebP, and delete the original.
     *
     * @param  UploadedFile|string  $file  Uploaded file instance or absolute file path
     * @param  string  $destinationRelativeDir  Destination directory relative to public_path() (e.g. 'uploads/gallery/photos')
     * @param  int  $maxWidth  Maximum width in pixels (aspect ratio maintained)
     * @param  int  $maxHeight  Maximum height in pixels (aspect ratio maintained)
     * @param  int  $quality  WebP compression quality (0-100)
     * @return string Relative path from public_path() to the optimized WebP file
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function optimize(
        UploadedFile|string $file,
        string $destinationRelativeDir = 'uploads/general',
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 82
    ): string {
        $sourcePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        if (! file_exists($sourcePath) || ! is_readable($sourcePath)) {
            throw new InvalidArgumentException("Source image does not exist or is not readable: {$sourcePath}");
        }

        $imageInfo = @getimagesize($sourcePath);
        if ($imageInfo === false) {
            throw new InvalidArgumentException("File is not a valid image: {$sourcePath}");
        }

        [$origWidth, $origHeight, $imageType] = $imageInfo;

        // Load image using appropriate GD function
        $srcImage = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            IMAGETYPE_GIF => @imagecreatefromgif($sourcePath),
            default => null,
        };

        if (! $srcImage) {
            throw new RuntimeException('Failed to decode image with GD. Unsupported or corrupted format.');
        }

        // Calculate dimensions preserving aspect ratio
        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $targetWidth = max(1, (int) round($origWidth * $ratio));
            $targetHeight = max(1, (int) round($origHeight * $ratio));
        }

        // Create target true-color GD canvas
        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve alpha transparency for PNG / WebP / GIF
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
        imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        imagealphablending($dstImage, true);

        // Resample with high quality interpolation
        imagecopyresampled(
            $dstImage,
            $srcImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        // Prepare destination directory
        $destinationDir = public_path(trim($destinationRelativeDir, '/\\'));
        if (! file_exists($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        // Generate unique WebP filename
        $filename = time().'_'.Str::random(12).'.webp';
        $fullDestinationPath = $destinationDir.DIRECTORY_SEPARATOR.$filename;

        // Save as WebP
        $saved = imagewebp($dstImage, $fullDestinationPath, $quality);

        // Free GD memory
        imagedestroy($srcImage);
        imagedestroy($dstImage);

        if (! $saved || ! file_exists($fullDestinationPath)) {
            throw new RuntimeException("Failed to save optimized WebP image to: {$fullDestinationPath}");
        }

        // If source is a temporary uploaded file or raw uncompressed file inside public uploads, delete original
        if ($file instanceof UploadedFile) {
            if (file_exists($sourcePath)) {
                @unlink($sourcePath);
            }
        } elseif (is_string($file) && file_exists($file) && $file !== $fullDestinationPath) {
            @unlink($file);
        }

        return trim($destinationRelativeDir, '/\\').'/'.$filename;
    }
}
