<?php

namespace Tests\Unit;

use App\Services\ImageOptimizerService;
use Tests\TestCase;

class ImageOptimizerDualEngineTest extends TestCase
{
    public function test_active_engine_prioritizes_gd_when_available(): void
    {
        $optimizer = new class extends ImageOptimizerService
        {
            public function isGdAvailable(): bool
            {
                return true;
            }

            public function isImagickAvailable(): bool
            {
                return true;
            }
        };

        $this->assertSame('gd', $optimizer->getActiveEngine());
    }

    public function test_active_engine_falls_back_to_imagick_when_gd_unavailable(): void
    {
        $optimizer = new class extends ImageOptimizerService
        {
            public function isGdAvailable(): bool
            {
                return false;
            }

            public function isImagickAvailable(): bool
            {
                return true;
            }
        };

        $this->assertSame('imagick', $optimizer->getActiveEngine());
        $status = $optimizer->getEnvironmentStatus();
        $this->assertSame('imagick', $status['active_engine']);
        $this->assertTrue($status['is_operational']);
    }

    public function test_active_engine_reports_none_when_neither_engine_is_available(): void
    {
        $optimizer = new class extends ImageOptimizerService
        {
            public function isGdAvailable(): bool
            {
                return false;
            }

            public function isImagickAvailable(): bool
            {
                return false;
            }
        };

        $this->assertSame('none', $optimizer->getActiveEngine());
        $status = $optimizer->getEnvironmentStatus();
        $this->assertFalse($status['is_operational']);
        $this->assertSame('none', $status['active_engine']);

        $testCompression = $optimizer->testCompression();
        $this->assertFalse($testCompression['success']);
        $this->assertSame('none', $testCompression['engine']);
    }

    public function test_optimizer_falls_back_to_direct_storage_when_engine_is_none(): void
    {
        $optimizer = new class extends ImageOptimizerService
        {
            public function isGdAvailable(): bool
            {
                return false;
            }

            public function isImagickAvailable(): bool
            {
                return false;
            }
        };

        $tempSource = tempnam(sys_get_temp_dir(), 'test_dual_').'.jpg';
        file_put_contents($tempSource, 'fake-jpeg-data');

        $savedRelative = $optimizer->optimize($tempSource, 'uploads/test_dual');
        $fullPath = public_path($savedRelative);

        $this->assertFileExists($fullPath);
        $this->assertStringEndsWith('.jpg', $savedRelative);

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
        @rmdir(public_path('uploads/test_dual'));
    }
}
