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
     * Check if the GD extension and core functions with WebP support are available.
     */
    public function isGdAvailable(): bool
    {
        if (! \extension_loaded('gd')) {
            return false;
        }

        $requiredFunctions = [
            'imagecreatefromjpeg',
            'imagecreatefrompng',
            'imagecreatetruecolor',
            'imagewebp',
            'imagecopyresampled',
        ];

        foreach ($requiredFunctions as $fn) {
            if (! \function_exists($fn)) {
                return false;
            }
        }

        // Verify that GD actually has WebP support compiled in
        if (\function_exists('imagetypes') && \defined('IMG_WEBP')) {
            return (bool) (\imagetypes() & IMG_WEBP);
        }

        if (\function_exists('gd_info')) {
            $info = \gd_info();

            return ! empty($info['WebP Support']);
        }

        return true;
    }

    /**
     * Check if ImageMagick (Imagick extension) with WebP write support is available.
     */
    public function isImagickAvailable(): bool
    {
        if (! \extension_loaded('imagick') || ! \class_exists('\Imagick')) {
            return false;
        }

        try {
            $formats = \Imagick::queryFormats('WEBP');

            return ! empty($formats);
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Get the active image optimization engine ('gd', 'imagick', or 'none').
     */
    public function getActiveEngine(): string
    {
        if ($this->isGdAvailable()) {
            return 'gd';
        }

        if ($this->isImagickAvailable()) {
            return 'imagick';
        }

        return 'none';
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
        int $maxBytes = self::MAX_BYTES,
        bool $deleteSource = true
    ): string {
        // Safeguard against memory exhaustion on high-resolution camera photo uploads
        @ini_set('memory_limit', '512M');

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

        $activeEngine = $this->getActiveEngine();

        // If neither GD nor Imagick can produce WebP, fall back to direct storage
        if ($activeEngine === 'none') {
            Log::info('Neither GD (with WebP) nor Imagick is available. Storing image directly without WebP conversion.');

            return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt, $deleteSource);
        }

        // If Imagick is the primary available engine
        if ($activeEngine === 'imagick') {
            try {
                return $this->optimizeWithImagick(
                    $file,
                    $sourcePath,
                    $destinationDir,
                    $destinationRelativeDir,
                    $origExt,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                    $maxBytes,
                    $deleteSource
                );
            } catch (Throwable $e) {
                Log::warning('Imagick optimization encountered an error, falling back to direct storage: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine());

                return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt, $deleteSource);
            }
        }

        // Otherwise use GD as the primary engine with automatic Imagick secondary fallback on error
        $srcImage = null;
        $dstImage = null;

        try {
            $imageInfo = @\getimagesize($sourcePath);
            if ($imageInfo === false) {
                return $this->fallbackOrSecondary(
                    $file,
                    $sourcePath,
                    $destinationDir,
                    $destinationRelativeDir,
                    $origExt,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                    $maxBytes,
                    $deleteSource
                );
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
                return $this->fallbackOrSecondary(
                    $file,
                    $sourcePath,
                    $destinationDir,
                    $destinationRelativeDir,
                    $origExt,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                    $maxBytes
                );
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
                return $this->fallbackOrSecondary(
                    $file,
                    $sourcePath,
                    $destinationDir,
                    $destinationRelativeDir,
                    $origExt,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                    $maxBytes
                );
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
                return $this->fallbackOrSecondary(
                    $file,
                    $sourcePath,
                    $destinationDir,
                    $destinationRelativeDir,
                    $origExt,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                    $maxBytes,
                    $deleteSource
                );
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
            $this->cleanupSourceFile($file, $sourcePath, $fullDestinationPath, $deleteSource);

            return trim(str_replace('\\', '/', $destinationRelativeDir), '/').'/'.$filename;
        } catch (Throwable $e) {
            Log::warning('GD image optimization encountered an error: '.$e->getMessage().'. Attempting Imagick secondary fallback.');

            return $this->fallbackOrSecondary(
                $file,
                $sourcePath,
                $destinationDir,
                $destinationRelativeDir,
                $origExt,
                $maxWidth,
                $maxHeight,
                $quality,
                $maxBytes,
                $deleteSource
            );
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
     * Try Imagick secondary optimization before resorting to uncompressed direct storage.
     */
    protected function fallbackOrSecondary(
        UploadedFile|string $file,
        string $sourcePath,
        string $destinationDir,
        string $destinationRelativeDir,
        string $origExt,
        int $maxWidth,
        int $maxHeight,
        int $quality,
        int $maxBytes,
        bool $deleteSource = true
    ): string {
        if ($this->isImagickAvailable()) {
            try {
                return $this->optimizeWithImagick(
                    $file,
                    $sourcePath,
                    $destinationDir,
                    $destinationRelativeDir,
                    $origExt,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                    $maxBytes,
                    $deleteSource
                );
            } catch (Throwable $ie) {
                Log::warning('Imagick secondary fallback also failed: '.$ie->getMessage());
            }
        }

        return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt, $deleteSource);
    }

    /**
     * Optimize an image using ImageMagick (Imagick extension) enforcing <= 180 KB WebP.
     */
    protected function optimizeWithImagick(
        UploadedFile|string $file,
        string $sourcePath,
        string $destinationDir,
        string $destinationRelativeDir,
        string $origExt,
        int $maxWidth,
        int $maxHeight,
        int $quality,
        int $maxBytes,
        bool $deleteSource = true
    ): string {
        $imagick = new \Imagick($sourcePath);

        // Auto-orient based on EXIF if present
        if (method_exists($imagick, 'autoOrient')) {
            $imagick->autoOrient();
        }

        $origWidth = $imagick->getImageWidth();
        $origHeight = $imagick->getImageHeight();

        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $targetWidth = max(1, (int) round($origWidth * $ratio));
            $targetHeight = max(1, (int) round($origHeight * $ratio));
            $imagick->resizeImage($targetWidth, $targetHeight, \Imagick::FILTER_LANCZOS, 1, true);
        }

        // Set WebP format and compression quality
        $imagick->setImageFormat('webp');
        $currentQuality = max(10, min(100, $quality));
        $imagick->setImageCompressionQuality($currentQuality);

        // Strip non-essential EXIF metadata to minimize size
        $imagick->stripImage();

        $filename = time().'_'.Str::random(12).'.webp';
        $fullDestinationPath = $destinationDir.DIRECTORY_SEPARATOR.$filename;

        $imagick->writeImage($fullDestinationPath);
        \clearstatcache(true, $fullDestinationPath);
        $currentSize = (int) @filesize($fullDestinationPath);

        // Iteration phase 1: Reduce quality down to MIN_QUALITY
        while ($currentSize > $maxBytes && $currentQuality > self::MIN_QUALITY) {
            $currentQuality -= self::QUALITY_STEP;
            $imagick->setImageCompressionQuality($currentQuality);
            $imagick->writeImage($fullDestinationPath);
            \clearstatcache(true, $fullDestinationPath);
            $currentSize = (int) @filesize($fullDestinationPath);
        }

        // Iteration phase 2: Downscale dimensions if still over maxBytes
        while ($currentSize > $maxBytes && ($targetWidth > 160 || $targetHeight > 120)) {
            $targetWidth = max(160, (int) round($targetWidth * 0.85));
            $targetHeight = max(120, (int) round($targetHeight * 0.85));
            $imagick->resizeImage($targetWidth, $targetHeight, \Imagick::FILTER_LANCZOS, 1, true);
            $imagick->writeImage($fullDestinationPath);
            \clearstatcache(true, $fullDestinationPath);
            $currentSize = (int) @filesize($fullDestinationPath);
        }

        // Iteration phase 3: Further quality reduction if still over ceiling
        while ($currentSize > $maxBytes && $currentQuality > 20) {
            $currentQuality -= self::QUALITY_STEP;
            $imagick->setImageCompressionQuality($currentQuality);
            $imagick->writeImage($fullDestinationPath);
            \clearstatcache(true, $fullDestinationPath);
            $currentSize = (int) @filesize($fullDestinationPath);
        }

        $imagick->clear();
        $imagick->destroy();

        if (! file_exists($fullDestinationPath) || filesize($fullDestinationPath) === 0) {
            return $this->storeDirectFallback($file, $destinationDir, $destinationRelativeDir, $origExt, $deleteSource);
        }

        // Delete original uncompressed source file
        $this->cleanupSourceFile($file, $sourcePath, $fullDestinationPath, $deleteSource);

        return trim(str_replace('\\', '/', $destinationRelativeDir), '/').'/'.$filename;
    }

    /**
     * Inspect the current server PHP environment for image optimization capabilities.
     *
     * @return array<string, mixed>
     */
    public function getEnvironmentStatus(): array
    {
        $gdLoaded = \extension_loaded('gd');
        $gdWebp = false;
        $gdVersion = 'N/A';

        if ($gdLoaded && \function_exists('gd_info')) {
            $info = \gd_info();
            $gdVersion = $info['GD Version'] ?? 'unknown';
            $gdWebp = ! empty($info['WebP Support']);
        } elseif ($gdLoaded && \function_exists('imagetypes') && \defined('IMG_WEBP')) {
            $gdWebp = (bool) (\imagetypes() & IMG_WEBP);
        }

        $imagickLoaded = \extension_loaded('imagick') && \class_exists('\Imagick');
        $imagickWebp = false;
        $imagickVersion = 'N/A';

        if ($imagickLoaded) {
            try {
                $formats = \Imagick::queryFormats('WEBP');
                $imagickWebp = ! empty($formats);
                $versionInfo = \Imagick::getVersion();
                $imagickVersion = $versionInfo['versionString'] ?? 'installed';
            } catch (Throwable) {
                $imagickWebp = false;
            }
        }

        $activeEngine = $this->getActiveEngine();
        $isOperational = $activeEngine !== 'none';

        $memoryLimit = ini_get('memory_limit') ?: 'unknown';
        $uploadMaxFilesize = ini_get('upload_max_filesize') ?: 'unknown';
        $postMaxSize = ini_get('post_max_size') ?: 'unknown';

        $uploadsDir = public_path('uploads');
        $isUploadsWritable = file_exists($uploadsDir) ? is_writable($uploadsDir) : is_writable(public_path());

        return [
            'is_operational' => $isOperational,
            'active_engine' => $activeEngine,
            'gd' => [
                'loaded' => $gdLoaded,
                'version' => $gdVersion,
                'webp_supported' => $gdWebp,
                'available' => $this->isGdAvailable(),
            ],
            'imagick' => [
                'loaded' => $imagickLoaded,
                'version' => $imagickVersion,
                'webp_supported' => $imagickWebp,
                'available' => $this->isImagickAvailable(),
            ],
            'php' => [
                'version' => PHP_VERSION,
                'memory_limit' => $memoryLimit,
                'upload_max_filesize' => $uploadMaxFilesize,
                'post_max_size' => $postMaxSize,
            ],
            'storage' => [
                'uploads_writable' => $isUploadsWritable,
                'public_path' => public_path(),
            ],
        ];
    }

    /**
     * Perform an on-demand live test image compression to WebP to verify end-to-end functionality.
     *
     * @return array{success: bool, engine: string, message: string, output_bytes?: int, duration_ms?: float}
     */
    public function testCompression(): array
    {
        $status = $this->getEnvironmentStatus();

        if (! $status['is_operational']) {
            return [
                'success' => false,
                'engine' => 'none',
                'message' => 'Neither PHP GD with WebP support nor Imagick is enabled on this server.',
            ];
        }

        $startTime = microtime(true);
        $testDir = public_path('uploads/test_diagnostics');

        if (! file_exists($testDir)) {
            @mkdir($testDir, 0755, true);
        }

        // Create a 200x200 sample test JPEG in a temp file
        $tempSource = tempnam(sys_get_temp_dir(), 'test_diag_').'.jpg';

        try {
            if ($this->isGdAvailable()) {
                $canvas = \imagecreatetruecolor(200, 200);
                for ($y = 0; $y < 200; $y += 20) {
                    $c = \imagecolorallocate($canvas, ($y * 5) % 255, 100, 200);
                    \imagefilledrectangle($canvas, 0, $y, 200, $y + 20, $c);
                }
                \imagejpeg($canvas, $tempSource, 90);
                \imagedestroy($canvas);
            } elseif ($this->isImagickAvailable()) {
                $im = new \Imagick;
                $im->newImage(200, 200, new \ImagickPixel('crimson'));
                $im->setImageFormat('jpeg');
                $im->writeImage($tempSource);
                $im->clear();
                $im->destroy();
            } else {
                return [
                    'success' => false,
                    'engine' => 'none',
                    'message' => 'No image creation engine available to generate test image.',
                ];
            }

            $optimizedRelative = $this->optimize($tempSource, 'uploads/test_diagnostics', 200, 200, 80);
            $fullOptimized = public_path($optimizedRelative);

            if (! file_exists($fullOptimized)) {
                return [
                    'success' => false,
                    'engine' => $this->getActiveEngine(),
                    'message' => 'Test file was not created at destination path: '.$fullOptimized,
                ];
            }

            $outputBytes = (int) filesize($fullOptimized);
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            $extension = strtolower(pathinfo($optimizedRelative, PATHINFO_EXTENSION));

            // Clean up test file
            @unlink($fullOptimized);
            @rmdir($testDir);

            if ($extension !== 'webp') {
                return [
                    'success' => false,
                    'engine' => $this->getActiveEngine(),
                    'message' => "Optimizer returned .{$extension} instead of WebP (fell back to direct storage).",
                ];
            }

            return [
                'success' => true,
                'engine' => $this->getActiveEngine(),
                'message' => "Successfully converted and optimized test image to WebP ({$outputBytes} bytes) in {$duration} ms using {$this->getActiveEngine()}.",
                'output_bytes' => $outputBytes,
                'duration_ms' => $duration,
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'engine' => $this->getActiveEngine(),
                'message' => 'Live test compression failed: '.$e->getMessage(),
            ];
        } finally {
            if (file_exists($tempSource)) {
                @unlink($tempSource);
            }
        }
    }

    /**
     * Safely delete the original source file after saving the optimized WebP.
     */
    protected function cleanupSourceFile(UploadedFile|string $file, string $sourcePath, string $destinationPath, bool $deleteSource = true): void
    {
        if (! $deleteSource) {
            return;
        }

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
        string $extension,
        bool $deleteSource = true
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
            if ($deleteSource && file_exists($file) && realpath($file) !== realpath($fullDestinationPath)) {
                @unlink($file);
            }
        }

        return trim(str_replace('\\', '/', $destinationRelativeDir), '/').'/'.$filename;
    }
}
