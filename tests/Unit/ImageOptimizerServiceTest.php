<?php

namespace Tests\Unit;

use App\Services\ImageOptimizerService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageOptimizerServiceTest extends TestCase
{
    protected ImageOptimizerService $optimizer;

    protected string $testTempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->optimizer = app(ImageOptimizerService::class);
        $this->testTempDir = public_path('uploads/test_unit');

        if (! file_exists($this->testTempDir)) {
            @mkdir($this->testTempDir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testTempDir)) {
            foreach (glob($this->testTempDir.'/*') ?: [] as $file) {
                @unlink($file);
            }
            @rmdir($this->testTempDir);
        }
        parent::tearDown();
    }

    public function test_optimizer_detects_gd_availability(): void
    {
        $this->assertTrue($this->optimizer->isGdAvailable());
    }

    public function test_optimizer_converts_image_to_webp_strictly_under_180kb_and_cleans_source(): void
    {
        // Generate a simulated 2000x1500 high-res image
        $tempSource = tempnam(sys_get_temp_dir(), 'test_img_').'.jpg';
        $canvas = imagecreatetruecolor(2000, 1500);

        // Fill with randomized gradient/noise to test compression resistance
        for ($y = 0; $y < 1500; $y += 30) {
            $color = imagecolorallocate($canvas, ($y * 7) % 255, ($y * 13) % 255, ($y * 19) % 255);
            imagefilledrectangle($canvas, 0, $y, 2000, $y + 30, $color);
        }

        imagejpeg($canvas, $tempSource, 95);
        imagedestroy($canvas);

        $this->assertFileExists($tempSource);
        $sourceSize = filesize($tempSource);
        $this->assertGreaterThan(50000, $sourceSize);

        // Execute optimization
        $savedRelativePath = $this->optimizer->optimize(
            $tempSource,
            'uploads/test_unit',
            1600,
            1600,
            82,
            ImageOptimizerService::MAX_BYTES
        );

        $savedFullPath = public_path($savedRelativePath);

        // Verify output properties
        $this->assertFileExists($savedFullPath);
        $this->assertStringEndsWith('.webp', $savedRelativePath);

        $outputSize = filesize($savedFullPath);
        $this->assertLessThanOrEqual(
            ImageOptimizerService::MAX_BYTES,
            $outputSize,
            "Optimized image size ({$outputSize} bytes) exceeded maximum ceiling of 180 KB"
        );

        // Verify source file was cleaned up / deleted
        $this->assertFileDoesNotExist($tempSource, 'Original source file was not deleted after optimization');

        // Cleanup
        if (file_exists($savedFullPath)) {
            @unlink($savedFullPath);
        }
    }

    public function test_optimizer_handles_uploaded_file_instance_and_deletes_temp(): void
    {
        $uploaded = UploadedFile::fake()->image('test_upload.png', 1200, 900);
        $tempPath = $uploaded->getRealPath();
        $this->assertFileExists($tempPath);

        $savedRelativePath = $this->optimizer->optimize(
            $uploaded,
            'uploads/test_unit',
            1200,
            900
        );

        $savedFullPath = public_path($savedRelativePath);

        $this->assertFileExists($savedFullPath);
        $this->assertStringEndsWith('.webp', $savedRelativePath);
        $this->assertLessThanOrEqual(ImageOptimizerService::MAX_BYTES, filesize($savedFullPath));

        // Cleanup
        if (file_exists($savedFullPath)) {
            @unlink($savedFullPath);
        }
    }

    public function test_optimizer_fallback_safely_stores_svg_with_svg_extension(): void
    {
        $svgPath = tempnam(sys_get_temp_dir(), 'test_svg_').'.svg';
        file_put_contents($svgPath, '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="100"><rect width="200" height="100" fill="blue"/></svg>');

        $savedRelativePath = $this->optimizer->optimize(
            $svgPath,
            'uploads/test_unit'
        );

        $savedFullPath = public_path($savedRelativePath);
        $this->assertFileExists($savedFullPath);
        $this->assertStringEndsWith('.svg', $savedRelativePath);

        if (file_exists($savedFullPath)) {
            @unlink($savedFullPath);
        }
    }

    public function test_optimizer_rejects_unsupported_extension_on_fallback(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported image format for fallback storage');

        $txtPath = tempnam(sys_get_temp_dir(), 'test_script_').'.sh';
        file_put_contents($txtPath, '#!/bin/bash\necho "hello"');

        $this->optimizer->optimize(
            $txtPath,
            'uploads/test_unit'
        );
    }
}
