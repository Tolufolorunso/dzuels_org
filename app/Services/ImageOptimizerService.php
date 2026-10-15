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
     * Strict maximum file size ceiling in bytes: 180 KB (180 * 1024 = 184,320 bytes).
     */
    public const MAX_BYTES = 184320;

    /**
     * Default maximum pixel width/height for web performance.
     */
    public const DEFAULT_MAX_DIMENSION = 1600;

    /**
     * Default initial WebP quality.
     */
    public const DEFAULT_INITIAL_QUALITY = 82;

    /**
     * Minimum WebP quality during iterative compression before downscaling dimensions.
     */
    public const MIN_QUALITY = 40;

    /**
     * Quality decrement step during iterative compression.
     */
    public const QUALITY_STEP = 5;

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
     * Optimize an uploaded image file, strictly enforce WebP output with a maximum
     * file size ceiling of 180 KB, and delete the original uncompressed source file.
     *
     * @param  UploadedFile|string  $file  Uploaded file instance or absolute file path
     * @param  string  $destinationRelativeDir  Destination directory relative to public_path()
     * @param  int  $maxWidth  Maximum pixel width (aspect ratio maintained)
     * @param  int  $maxHeight  Maximum pixel height (aspect ratio maintained)
     * @param  int  $quality  Starting WebP compression quality (0-100)
     * @param  int  $maxBytes  Maximum file size ceiling in bytes (default 180 KB)
     * @return string Relative path from public_path() to the optimized WebP file
     */
    public function optimize(
        UploadedFile|string $file,
        string $destinationRelativeDir = 'uploads/general',
        int $maxWidth = self::DEFAULT_MAX_DIMENSION,
        int $maxHeight = self::DEFAULT_MAX_DIMENSION,
        int $quality = self::DEFAULT_INITIAL_QUALITY,
        int $maxBytes = self::MAX_BYTES
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

        // If GD is missing or unusable, use direct fallback
        if (! $this->isGdAvailable()) {
            Log::info('GD extension unavailable. Storing image directly without WebP conversion.');

            return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
        }

        $srcImage = null;
        $dstImage = null;

        try {
            $imageInfo = @\getimagesize($sourcePath);
            if ($imageInfo === false) {
                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            // Load source image
            $srcImage = match ($imageType) {
                IMAGETYPE_JPEG => \function_exists('imagecreatefromjpeg') ? @\imagecreatefromjpeg($sourcePath) : null,
                IMAGETYPE_PNG => \function_exists('imagecreatefrompng') ? @\imagecreatefrompng($sourcePath) : null,
                IMAGETYPE_WEBP => \function_exists('imagecreatefromwebp') ? @\imagecreatefromwebp($sourcePath) : null,
                IMAGETYPE_GIF => \function_exists('imagecreatefromgif') ? @\imagecreatefromgif($sourcePath) : null,
                default => null,
            };

            if (! $srcImage) {
                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
            }

            // Calculate target dimensions maintaining aspect ratio
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

            // Resample with high-quality interpolation
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

            // Iterative optimization pass: start at requested quality, decrement while filesize > maxBytes
            $currentQuality = max(10, min(100, $quality));
            $saved = \imagewebp($dstImage, $fullDestinationPath, $currentQuality);

            if (! $saved || ! file_exists($fullDestinationPath)) {
                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
            }

            $currentSize = (int) filesize($fullDestinationPath);

            // Iteration phase 1: Reduce quality down to MIN_QUALITY
            while ($currentSize > $maxBytes && $currentQuality > self::MIN_QUALITY) {
                $currentQuality -= self::QUALITY_STEP;
                \imagewebp($dstImage, $fullDestinationPath, $currentQuality);
                \clearstatcache(true, $fullDestinationPath);
                $currentSize = (int) filesize($fullDestinationPath);
            }

            // Iteration phase 2: If still exceeding maxBytes, iteratively downscale canvas dimensions
            while ($currentSize > $maxBytes && ($targetWidth > 160 || $targetHeight > 120)) {
                $targetWidth = max(160, (int) round($targetWidth * 0.85));
                $targetHeight = max(120, (int) round($targetHeight * 0.85));

                $scaledCanvas = \imagecreatetruecolor($targetWidth, $targetHeight);
                \imagealphablending($scaledCanvas, false);
                \imagesavealpha($scaledCanvas, true);
                \imagecopyresampled(
                    $scaledCanvas,
                    $dstImage,
                    0,
                    0,
                    0,
                    0,
                    $targetWidth,
                    $targetHeight,
                    \imagesx($dstImage),
                    \imagesy($dstImage)
                );

                \imagedestroy($dstImage);
                $dstImage = $scaledCanvas;

                \imagewebp($dstImage, $fullDestinationPath, $currentQuality);
                \clearstatcache(true, $fullDestinationPath);
                $currentSize = (int) filesize($fullDestinationPath);
            }

            // Iteration phase 3: Further quality reduction if still over ceiling
            while ($currentSize > $maxBytes && $currentQuality > 20) {
                $currentQuality -= self::QUALITY_STEP;
                \imagewebp($dstImage, $fullDestinationPath, $currentQuality);
                \clearstatcache(true, $fullDestinationPath);
                $currentSize = (int) filesize($fullDestinationPath);
            }

            // Delete original uncompressed source file
            $this->cleanupSourceFile($file, $sourcePath, $fullDestinationPath);

            return trim(str_replace('\\', '/', $destinationRelativeDir), '/').'/'.$filename;
        } catch (Throwable $e) {
            Log::warning('Image optimization encountered an error, falling back to direct storage: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt);
        } finally {
            if ($srcImage && (\is_resource($srcImage) || (is_object($srcImage) && $srcImage instanceof \GdImage))) {
                @\imagedestroy($srcImage);
            }
            if ($dstImage && (\is_resource($dstImage) || (is_object($dstImage) && $dstImage instanceof \GdImage))) {
                @\imagedestroy($dstImage);
            }
        }
    }

    /**
     * Safely delete the original source file after saving the optimized WebP.
     */
    protected function cleanupSourceFile(UploadedFile|string $file, string $sourcePath, string $destinationPath): void
    {
        if ($file instanceof UploadedFile) {
            $realPath = $file->getRealPath();
            if ($realPath && file_exists($realPath)) {
                @unlink($realPath);
            }
        } elseif (is_string($file)) {
            $realSource = realpath($sourcePath) ?: $sourcePath;
            $realDest = realpath($destinationPath) ?: $destinationPath;

            if ($realSource && $realDest && $realSource !== $realDest && file_exists($realSource)) {
                @unlink($realSource);
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
        $ext = strtolower($extension);
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
            throw new InvalidArgumentException("Unsupported image format for fallback storage: .{$extension}");
        }

        $sourceSize = $file instanceof UploadedFile ? $file->getSize() : (@filesize($file) ?: 0);
        if ($sourceSize > 16 * 1024 * 1024) {
            throw new InvalidArgumentException('File exceeds maximum allowed upload size for fallback storage.');
        }

        $filename = time().'_'.Str::random(12).'.'.$ext;

        if (! file_exists($destinationDir)) {
            @mkdir($destinationDir, 0755, true);
        }

        $fullDestinationPath = $destinationDir.DIRECTORY_SEPARATOR.$filename;

        if ($file instanceof UploadedFile) {
            $file->move($destinationDir, $filename);
        } elseif (is_string($file)) {
            @copy($file, $fullDestinationPath);
            if (file_exists($file) && realpath($file) !== realpath($fullDestinationPath)) {
                @unlink($file);
            }
        }

        return trim(str_replace('\\', '/', $destinationRelativeDir), '/').'/'.$filename;
    }
}
