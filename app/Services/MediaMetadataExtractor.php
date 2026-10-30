<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Throwable;

class MediaMetadataExtractor
{
    /**
     * Extract rich metadata (taken_at, color_space, dimensions, mime, exif) from an image file.
     *
     * @param  string  $path  Relative web path (e.g. 'uploads/media/photo.webp') or absolute filesystem path.
     * @return array{
     *     taken_at: ?Carbon,
     *     color_space: string,
     *     width: ?int,
     *     height: ?int,
     *     file_size: int,
     *     mime_type: string,
     *     camera_make: ?string,
     *     camera_model: ?string,
     *     exposure: ?string,
     *     iso: ?string,
     *     focal_length: ?string,
     *     has_exif: bool
     * }
     */
    public function extract(string $path): array
    {
        $absolutePath = $this->resolveAbsolutePath($path);

        if (! File::exists($absolutePath) || ! is_file($absolutePath)) {
            return [
                'taken_at' => null,
                'color_space' => 'sRGB',
                'width' => null,
                'height' => null,
                'file_size' => 0,
                'mime_type' => 'image/webp',
                'camera_make' => null,
                'camera_model' => null,
                'exposure' => null,
                'iso' => null,
                'focal_length' => null,
                'has_exif' => false,
            ];
        }

        $fileSize = (int) @filesize($absolutePath);
        $rawImageInfo = @getimagesize($absolutePath);
        $imageInfo = is_array($rawImageInfo) ? $rawImageInfo : null;

        $width = $imageInfo[0] ?? null;
        $height = $imageInfo[1] ?? null;
        $mimeType = $imageInfo['mime'] ?? (File::mimeType($absolutePath) ?: 'image/webp');

        $exifData = [];
        $hasExif = false;

        if (function_exists('exif_read_data')) {
            try {
                // EXIF is typically present in JPEG, TIFF, and some WebP
                $rawExif = @exif_read_data($absolutePath, 'ANY_TAG', true, false);
                if (is_array($rawExif)) {
                    $hasExif = true;
                    // Flatten EXIF sections into single associative array
                    foreach ($rawExif as $section => $data) {
                        if (is_array($data)) {
                            foreach ($data as $key => $val) {
                                if (! isset($exifData[$key]) && ! is_array($val)) {
                                    $exifData[$key] = $val;
                                }
                            }
                        } elseif (! is_array($data)) {
                            $exifData[$section] = $data;
                        }
                    }
                }
            } catch (Throwable) {
                // Silently swallow unreadable or corrupted EXIF blocks
            }
        }

        // 1. Determine Capture Timestamp (taken_at)
        $takenAt = $this->resolveCaptureTime($exifData);

        // 2. Determine Color Space
        $colorSpace = $this->resolveColorSpace($exifData, $imageInfo);

        // 3. Extract Camera & Lens details
        $cameraMake = isset($exifData['Make']) ? trim((string) $exifData['Make']) : null;
        $cameraModel = isset($exifData['Model']) ? trim((string) $exifData['Model']) : null;
        $exposure = isset($exifData['ExposureTime']) ? trim((string) $exifData['ExposureTime']) : null;
        $iso = isset($exifData['ISOSpeedRatings']) ? (string) $exifData['ISOSpeedRatings'] : null;
        $focalLength = isset($exifData['FocalLength']) ? (string) $exifData['FocalLength'] : null;

        return [
            'taken_at' => $takenAt,
            'color_space' => $colorSpace,
            'width' => $width,
            'height' => $height,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'camera_make' => $cameraMake,
            'camera_model' => $cameraModel,
            'exposure' => $exposure,
            'iso' => $iso,
            'focal_length' => $focalLength,
            'has_exif' => $hasExif,
        ];
    }

    /**
     * Resolve the filesystem absolute path.
     */
    protected function resolveAbsolutePath(string $path): string
    {
        if (File::exists($path)) {
            return $path;
        }

        $cleanPath = ltrim(parse_url($path, PHP_URL_PATH) ?? $path, '/');

        return public_path($cleanPath);
    }

    /**
     * Parse DateTimeOriginal, DateTimeDigitized, or DateTime from EXIF tags.
     */
    protected function resolveCaptureTime(array $exif): ?Carbon
    {
        $candidateKeys = [
            'DateTimeOriginal',
            'DateTimeDigitized',
            'DateTime',
            'SubSecDateTimeOriginal',
        ];

        foreach ($candidateKeys as $key) {
            if (! empty($exif[$key]) && is_string($exif[$key])) {
                $rawDate = trim($exif[$key]);

                // Standard EXIF format: "YYYY:MM:DD HH:MM:SS"
                if (preg_match('/^(\d{4})[:\-](\d{2})[:\-](\d{2})\s+(\d{2}):(\d{2}):(\d{2})/', $rawDate, $matches)) {
                    try {
                        return Carbon::create(
                            (int) $matches[1],
                            (int) $matches[2],
                            (int) $matches[3],
                            (int) $matches[4],
                            (int) $matches[5],
                            (int) $matches[6]
                        );
                    } catch (Throwable) {
                        // Fall back to Carbon::parse
                    }
                }

                try {
                    return Carbon::parse($rawDate);
                } catch (Throwable) {
                    // Continue to next candidate
                }
            }
        }

        return null;
    }

    /**
     * Determine ColorSpace (sRGB, Adobe RGB, Display P3, etc.).
     */
    protected function resolveColorSpace(array $exif, ?array $imageInfo): string
    {
        if (isset($exif['ColorSpace'])) {
            $val = (int) $exif['ColorSpace'];
            if ($val === 1) {
                return 'sRGB';
            }
            if ($val === 2) {
                return 'Adobe RGB';
            }
            if ($val === 65535) {
                // 65535 indicates uncalibrated, but usually wide gamut / Adobe RGB if specified in ICC Profile
                return 'Adobe RGB (Wide Gamut)';
            }
        }

        // Inspect channels from getimagesize
        if (isset($imageInfo['channels'])) {
            if ($imageInfo['channels'] === 4) {
                return 'CMYK';
            }
        }

        // Standard digital photography default
        return 'sRGB';
    }
}
