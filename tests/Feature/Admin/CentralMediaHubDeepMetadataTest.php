<?php

namespace Tests\Feature\Admin;

use App\Models\MediaItem;
use App\Models\User;
use App\Services\ImageOptimizerService;
use App\Services\MediaMetadataExtractor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CentralMediaHubDeepMetadataTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_media_items_table_has_taken_at_and_color_space_columns(): void
    {
        $this->assertTrue(Schema::hasColumn('media_items', 'taken_at'));
        $this->assertTrue(Schema::hasColumn('media_items', 'color_space'));
    }

    public function test_media_item_model_casts_and_accessors(): void
    {
        $takenAt = Carbon::create(2026, 10, 17, 14, 30, 0);

        $item = MediaItem::create([
            'filename' => 'itd_keynote_2026.webp',
            'file_path' => 'uploads/media/itd_keynote_2026.webp',
            'file_size' => 245000,
            'mime_type' => 'image/webp',
            'width' => 1920,
            'height' => 1080,
            'title' => 'Ijero Tech Day Keynote',
            'source_type' => 'gallery',
            'year' => 2026,
            'taken_at' => $takenAt,
            'color_space' => 'sRGB',
        ]);

        $this->assertInstanceOf(Carbon::class, $item->taken_at);
        $this->assertEquals('2026-10-17 14:30:00', $item->taken_at->format('Y-m-d H:i:s'));
        $this->assertEquals('Oct 17, 2026, 02:30 PM', $item->formatted_taken_at);
        $this->assertEquals('Oct 17, 2026', $item->capture_date_formatted);
        $this->assertEquals('16:9', $item->aspect_ratio);
        $this->assertEquals('sRGB', $item->color_space_badge);
        $this->assertEquals('239.3 KB', $item->formatted_size);
    }

    public function test_media_metadata_extractor_handles_missing_file_gracefully(): void
    {
        $extractor = new MediaMetadataExtractor;
        $meta = $extractor->extract('non_existent_folder/missing_photo.webp');

        $this->assertNull($meta['taken_at']);
        $this->assertEquals('sRGB', $meta['color_space']);
        $this->assertEquals(0, $meta['file_size']);
        $this->assertFalse($meta['has_exif']);
    }

    public function test_media_metadata_extractor_extracts_real_image_dimensions(): void
    {
        $file = UploadedFile::fake()->image('camera_snap.jpg', 1200, 800);
        $extractor = new MediaMetadataExtractor;

        $meta = $extractor->extract($file->getRealPath());

        $this->assertEquals(1200, $meta['width']);
        $this->assertEquals(800, $meta['height']);
        $this->assertStringStartsWith('image/', $meta['mime_type']);
        $this->assertEquals('sRGB', $meta['color_space']);
        $this->assertGreaterThan(0, $meta['file_size']);
    }

    public function test_admin_can_view_media_hub_with_custom_per_page_pagination(): void
    {
        for ($i = 1; $i <= 60; $i++) {
            MediaItem::create([
                'filename' => "sample_batch_{$i}.webp",
                'file_path' => "uploads/media/sample_batch_{$i}.webp",
                'file_size' => 100000 + $i,
                'mime_type' => 'image/webp',
                'width' => 800,
                'height' => 600,
                'title' => "Batch Sample Photo {$i}",
                'source_type' => 'gallery',
            ]);
        }

        // Default pagination is 80 items
        $responseDefault = $this->actingAs($this->admin)->get('/admin/media');
        $responseDefault->assertOk();
        $responseDefault->assertSee('(80 per page)');
        $responseDefault->assertSee('Per Page');

        // Configurable pagination to 150 items
        $response150 = $this->actingAs($this->admin)->get('/admin/media?per_page=150');
        $response150->assertOk();
        $response150->assertSee('(150 per page)');

        // Invalid pagination falls back to 80
        $responseInvalid = $this->actingAs($this->admin)->get('/admin/media?per_page=9999');
        $responseInvalid->assertOk();
        $responseInvalid->assertSee('(80 per page)');
    }

    public function test_admin_can_sort_media_by_captured_date_and_dimensions(): void
    {
        $olderCapture = MediaItem::create([
            'filename' => 'capture_old.webp',
            'file_path' => 'uploads/media/capture_old.webp',
            'file_size' => 200000,
            'mime_type' => 'image/webp',
            'width' => 1000,
            'height' => 500, // 500,000 pixels
            'title' => 'Older Capture Event',
            'source_type' => 'gallery',
            'taken_at' => Carbon::create(2024, 5, 1, 10, 0, 0),
        ]);

        $newerCapture = MediaItem::create([
            'filename' => 'capture_new.webp',
            'file_path' => 'uploads/media/capture_new.webp',
            'file_size' => 500000,
            'mime_type' => 'image/webp',
            'width' => 4000,
            'height' => 3000, // 12,000,000 pixels
            'title' => 'Newer Capture Event',
            'source_type' => 'gallery',
            'taken_at' => Carbon::create(2026, 10, 15, 12, 0, 0),
        ]);

        // 1. Sort by captured_desc
        $responseCapturedDesc = $this->actingAs($this->admin)->get('/admin/media?sort=captured_desc');
        $responseCapturedDesc->assertOk();
        $content = $responseCapturedDesc->getContent();
        $posNewer = strpos($content, 'Newer Capture Event');
        $posOlder = strpos($content, 'Older Capture Event');
        $this->assertTrue($posNewer < $posOlder, 'Newer capture should appear before older capture in captured_desc sort');

        // 2. Sort by captured_asc
        $responseCapturedAsc = $this->actingAs($this->admin)->get('/admin/media?sort=captured_asc');
        $responseCapturedAsc->assertOk();
        $contentAsc = $responseCapturedAsc->getContent();
        $posNewerAsc = strpos($contentAsc, 'Newer Capture Event');
        $posOlderAsc = strpos($contentAsc, 'Older Capture Event');
        $this->assertTrue($posOlderAsc < $posNewerAsc, 'Older capture should appear before newer capture in captured_asc sort');

        // 3. Sort by dimensions_desc
        $responseDimensionsDesc = $this->actingAs($this->admin)->get('/admin/media?sort=dimensions_desc');
        $responseDimensionsDesc->assertOk();
        $contentDimDesc = $responseDimensionsDesc->getContent();
        $posNewerDim = strpos($contentDimDesc, 'Newer Capture Event');
        $posOlderDim = strpos($contentDimDesc, 'Older Capture Event');
        $this->assertTrue($posNewerDim < $posOlderDim, 'Higher resolution image should appear before lower resolution in dimensions_desc sort');
    }

    public function test_admin_can_view_zoom_controls_and_inspector_modal_in_index(): void
    {
        $item = MediaItem::create([
            'filename' => 'inspector_target.webp',
            'file_path' => 'uploads/media/inspector_target.webp',
            'file_size' => 150000,
            'mime_type' => 'image/webp',
            'width' => 1920,
            'height' => 1080,
            'title' => 'Inspector Target Photo',
            'caption' => 'Inspection caption details',
            'source_type' => 'gallery',
            'color_space' => 'sRGB',
            'taken_at' => Carbon::create(2026, 10, 17, 9, 0, 0),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/media');
        $response->assertOk();

        // Zoom controls
        $response->assertSee('zoomLevel');
        $response->assertSee('setZoom');
        $response->assertSee('dzuels_media_zoom');
        $response->assertSee('Compact');
        $response->assertSee('Normal');
        $response->assertSee('Large');
        $response->assertSee('X-Large');

        // Metadata badges
        $response->assertSee('1920 × 1080');
        $response->assertSee('16:9');
        $response->assertSee('sRGB');

        // Inspector modal
        $response->assertSee('inspectorItem');
        $response->assertSee('openInspector');
        $response->assertSee('Technical Specifications');
        $response->assertSee('Close Inspector');
    }

    public function test_upload_extracts_metadata_and_populates_taken_at_and_color_space(): void
    {
        $optimizerMock = $this->mock(ImageOptimizerService::class);
        $optimizerMock->shouldReceive('optimize')
            ->once()
            ->andReturn('uploads/media/extracted_photo.webp');

        $file = UploadedFile::fake()->image('camera_shot.jpg', 1600, 900);

        $response = $this->actingAs($this->admin)->postJson('/admin/media/upload', [
            'image' => $file,
            'title' => 'Camera Shot with EXIF',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'item' => [
                'filename' => 'extracted_photo.webp',
                'file_path' => 'uploads/media/extracted_photo.webp',
                'color_space' => 'sRGB',
                'dimensions' => '1600 × 900',
            ],
        ]);

        $this->assertDatabaseHas('media_items', [
            'filename' => 'extracted_photo.webp',
            'file_path' => 'uploads/media/extracted_photo.webp',
            'width' => 1600,
            'height' => 900,
            'color_space' => 'sRGB',
        ]);
    }

    public function test_extract_media_metadata_artisan_command_backfills_items(): void
    {
        $item = MediaItem::create([
            'filename' => 'backfill_target.webp',
            'file_path' => 'uploads/media/backfill_target.webp',
            'file_size' => 100000,
            'mime_type' => 'image/webp',
            'width' => null,
            'height' => null,
            'taken_at' => null,
            'color_space' => null,
            'title' => 'Backfill Target',
        ]);

        $this->artisan('media:extract-metadata', ['--limit' => 10])
            ->assertExitCode(0);

        $item->refresh();
        $this->assertNotNull($item->color_space);
        $this->assertEquals('sRGB', $item->color_space);
    }
}
