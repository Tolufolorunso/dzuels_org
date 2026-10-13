<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class ImageOptimizerService
{
    /**
     * Check if the GD extension and core functions are available.
     */
    public function isGdAvailable(): bool
    {
        return \extension_loaded('gd')
            && \function_exists('imagecreatefromjpeg')
            && \function_exists('imagewebp')
            && \function_exists('imagecreatetruecolor');
    }

    /**
     * Optimize an uploaded image file, resize if needed, convert to WebP,
     * and delete the original. If GD is unavailable or fails, gracefully
     * falls back to storing the original file directly without throwing.
     *
     * @param  UploadedFile|string  $file  Uploaded file instance or absolute file path
     * @param  string  $destinationRelativeDir  Destination directory relative to public_path() (e.g. 'uploads/gallery/photos')
     * @param  int  $maxWidth  Maximum width in pixels (aspect ratio maintained)
     * @param  int  $maxHeight  Maximum height in pixels (aspect ratio maintained)
     * @param  int  $quality  WebP compression quality (0-100)
     * @return string Relative path from public_path() to the stored file
     */
    public function optimize(
        UploadedFile|string $file,
        string $destinationRelativeDir = 'uploads/general',
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 82
    ): string {
        $sourcePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        if (! $sourcePath || ! file_exists($sourcePath) || ! is_readable($sourcePath)) {
            throw new InvalidArgumentException("Source image does not exist or is not readable: {$sourcePath}");
        }

        $destinationDir = public_path(trim($destinationRelativeDir, '/\\'));
        if (! file_exists($destinationDir)) {
            @mkdir($destinationDir, 0755, true);
        }

        // Determine original extension
        $origExt = 'jpg';
        if ($file instanceof UploadedFile) {
            $origExt = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        } elseif (is_string($file)) {
            $origExt = strtolower(pathinfo($file, PATHINFO_EXTENSION) ?: 'jpg');
        }

        // If GD is not loaded or missing core functions, immediately use direct fallback
        if (! $this->isGdAvailable()) {
            Log::info('GD extension unavailable. Storing image directly without WebP conversion.');

            return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
        }

        $srcImage = null;
        $dstImage = null;

        try {
            $imageInfo = @\getimagesize($sourcePath);
            if ($imageInfo === false) {
                // Not recognizable by GD getimagesize, fall back to direct storage
                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            // Load image using globally qualified GD function
            $srcImage = match ($imageType) {
                IMAGETYPE_JPEG => \function_exists('imagecreatefromjpeg') ? @\imagecreatefromjpeg($sourcePath) : null,
                IMAGETYPE_PNG => \function_exists('imagecreatefrompng') ? @\imagecreatefrompng($sourcePath) : null,
                IMAGETYPE_WEBP => \function_exists('imagecreatefromwebp') ? @\imagecreatefromwebp($sourcePath) : null,
                IMAGETYPE_GIF => \function_exists('imagecreatefromgif') ? @\imagecreatefromgif($sourcePath) : null,
                default => null,
            };

            if (! $srcImage) {
                // Decoding failed, fall back safely
                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
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
            $dstImage = \imagecreatetruecolor($targetWidth, $targetHeight);
            if (! $dstImage) {
                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
            }

            // Preserve alpha transparency for PNG / WebP / GIF
            \imagealphablending($dstImage, false);
            \imagesavealpha($dstImage, true);
            $transparent = \imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            \imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);
            \imagealphablending($dstImage, true);

            // Resample with high quality interpolation
            \imagecopyresampled(
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

            // Generate unique WebP filename
            $filename = time().'_'.Str::random(12).'.webp';
            $fullDestinationPath = $destinationDir.DIRECTORY_SEPARATOR.$filename;

            // Save as WebP
            $saved = \imagewebp($dstImage, $fullDestinationPath, $quality);

            if (! $saved || ! file_exists($fullDestinationPath)) {
                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
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
        } catch (Throwable $e) {
            Log::warning('Image optimization encountered an error, falling back to direct storage: '.$e->getMessage());

            return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
        } finally {
            if ($srcImage && \is_resource($srcImage) || (is_object($srcImage) && $srcImage instanceof \GdImage)) {
                @\imagedestroy($srcImage);
            }
            if ($dstImage && \is_resource($dstImage) || (is_object($dstImage) && $dstImage instanceof \GdImage)) {
                @\imagedestroy($dstImage);
            }
        }
    }

    /**
     * Fallback to store file directly without crashing if GD is absent or fails.
     */
    protected function storeDirectFallback(
        UploadedFile|string $file,
        string $destinationDir,
        string $destinationRelativeDir,
        string $extension
    ): string {
        $safeExt = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']) ? strtolower($extension) : 'jpg';
        $filename = time().'_'.Str::random(12).'.'.$safeExt;
        $fullDestinationPath = $destinationDir.DIRECTORY_SEPARATOR.$filename;

        if ($file instanceof UploadedFile) {
            $file->move($destinationDir, $filename);
        } elseif (is_string($file)) {
            @copy($file, $fullDestinationPath);
        }

        return trim($destinationRelativeDir, '/\\').'/'.$filename;
    }
}
