<?php

namespace Tests\Feature;

use App\Services\ImageOptimizerService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageOptimizerResilienceTest extends TestCase
{
    public function test_service_reports_gd_availability(): void
    {
        $service = new ImageOptimizerService;
        $this->assertIsBool($service->isGdAvailable());
    }

    public function test_optimizes_uploaded_image_successfully(): void
    {
        $service = new ImageOptimizerService;
        $file = UploadedFile::fake()->image('test-photo.jpg', 600, 400);

        $resultPath = $service->optimize($file, 'uploads/test', 300, 200);

        $this->assertNotEmpty($resultPath);
        $fullPath = public_path($resultPath);
        $this->assertFileExists($fullPath);

        // Clean up
        @unlink($fullPath);
        @rmdir(dirname($fullPath));
    }

    public function test_direct_fallback_stores_file_safely(): void
    {
        $service = new ImageOptimizerService;
        // Create a fake non-standard image that might fail GD or raw file
        $file = UploadedFile::fake()->create('raw-file.png', 50, 'image/png');

        $resultPath = $service->optimize($file, 'uploads/test-fallback');

        $this->assertNotEmpty($resultPath);
        $fullPath = public_path($resultPath);
        $this->assertFileExists($fullPath);

        // Clean up
        @unlink($fullPath);
        @rmdir(dirname($fullPath));
    }
}
