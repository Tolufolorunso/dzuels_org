<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CentralMediaHubBulkAndDateManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    protected function createMediaItem(array $attributes = []): MediaItem
    {
        static $counter = 1;
        $counter++;

        return MediaItem::create(array_merge([
            'filename' => "test_photo_{$counter}.webp",
            'file_path' => "uploads/media/test_photo_{$counter}.webp",
            'file_size' => 100000,
            'mime_type' => 'image/webp',
            'width' => 1200,
            'height' => 800,
            'title' => "Test Photo {$counter}",
            'source_type' => 'general',
            'is_public' => true,
            'year' => 2024,
        ], $attributes));
    }

    public function test_default_pagination_is_80_and_supports_high_capacity_options(): void
    {
        // Create 85 media items
        for ($i = 0; $i < 85; $i++) {
            $this->createMediaItem();
        }

        // Default query without per_page should paginate with 80
        $response = $this->actingAs($this->admin)->get(route('admin.media.index'));
        $response->assertOk();
        $response->assertViewHas('currentPerPage', 80);
        $this->assertCount(80, $response->viewData('mediaItems'));

        // Query with per_page=150 should return all 85 items
        $response150 = $this->actingAs($this->admin)->get(route('admin.media.index', ['per_page' => 150]));
        $response150->assertOk();
        $response150->assertViewHas('currentPerPage', 150);
        $this->assertCount(85, $response150->viewData('mediaItems'));

        // Query with invalid per_page (e.g. 10) falls back to 80
        $responseInvalid = $this->actingAs($this->admin)->get(route('admin.media.index', ['per_page' => 10]));
        $responseInvalid->assertOk();
        $responseInvalid->assertViewHas('currentPerPage', 80);
        $this->assertCount(80, $responseInvalid->viewData('mediaItems'));
    }

    public function test_updating_media_with_full_calendar_date_updates_taken_at_and_syncs_year(): void
    {
        $item = $this->createMediaItem([
            'year' => 2024,
            'taken_at' => null,
        ]);

        $response = $this->actingAs($this->admin)->putJson(route('admin.media.update', $item), [
            'title' => 'Corrected Sample Photo',
            'taken_at' => '2025-10-18',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $item->refresh();
        $this->assertEquals('Corrected Sample Photo', $item->title);
        $this->assertNotNull($item->taken_at);
        $this->assertEquals('2025-10-18', $item->taken_at->format('Y-m-d'));
        $this->assertEquals(2025, $item->year);
    }

    public function test_bulk_action_assigns_album_across_selected_items(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Tech Day 2025 Highlights',
            'slug' => 'tech-day-2025-highlights',
            'kicker' => 'Flagship Event',
            'is_active' => true,
        ]);

        $item1 = $this->createMediaItem(['gallery_album_id' => null, 'source_type' => 'general']);
        $item2 = $this->createMediaItem(['gallery_album_id' => null, 'source_type' => 'general']);
        $item3 = $this->createMediaItem(['gallery_album_id' => null, 'source_type' => 'general']);

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.bulk-action'), [
            'action' => 'assign_album',
            'media_ids' => [$item1->id, $item2->id, $item3->id],
            'gallery_album_id' => $album->id,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'affected' => 3,
        ]);

        foreach ([$item1, $item2, $item3] as $item) {
            $item->refresh();
            $this->assertEquals($album->id, $item->gallery_album_id);
            $this->assertEquals('gallery', $item->source_type);
        }
    }

    public function test_bulk_action_sets_visibility_across_selected_items_and_linked_photos(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Reading Challenge',
            'slug' => 'reading-challenge',
            'is_active' => true,
        ]);

        $photo = $album->photos()->create([
            'image_path' => 'uploads/gallery/sample.webp',
            'is_public' => true,
            'sort_order' => 1,
        ]);

        $item = $this->createMediaItem([
            'file_path' => 'uploads/gallery/sample.webp',
            'is_public' => true,
            'gallery_photo_id' => $photo->id,
        ]);

        // Bulk toggle to hidden (is_public = false)
        $response = $this->actingAs($this->admin)->postJson(route('admin.media.bulk-action'), [
            'action' => 'set_visibility',
            'media_ids' => [$item->id],
            'is_public' => false,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'affected' => 1,
        ]);

        $item->refresh();
        $photo->refresh();
        $this->assertFalse((bool) $item->is_public);
        $this->assertFalse((bool) $photo->is_public);
    }

    public function test_bulk_action_updates_date_and_year_across_selected_items(): void
    {
        $item1 = $this->createMediaItem(['year' => 2024, 'taken_at' => null]);
        $item2 = $this->createMediaItem(['year' => 2024, 'taken_at' => null]);

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.bulk-action'), [
            'action' => 'update_date',
            'media_ids' => [$item1->id, $item2->id],
            'taken_at' => '2025-11-05',
            'year' => 2025,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'affected' => 2,
        ]);

        foreach ([$item1, $item2] as $item) {
            $item->refresh();
            $this->assertEquals(2025, $item->year);
            $this->assertEquals('2025-11-05', $item->taken_at?->format('Y-m-d'));
        }
    }

    public function test_bulk_action_deletes_selected_items_safely(): void
    {
        // Fake file inside uploads/
        $testPath = public_path('uploads/media/bulk_delete_test.webp');
        File::ensureDirectoryExists(dirname($testPath));
        File::put($testPath, 'fake-content');

        $item = $this->createMediaItem([
            'filename' => 'bulk_delete_test.webp',
            'file_path' => 'uploads/media/bulk_delete_test.webp',
            'file_size' => 100,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.bulk-action'), [
            'action' => 'delete',
            'media_ids' => [$item->id],
        ]);

        $response->assertOk();
        $this->assertDatabaseMissing('media_items', ['id' => $item->id]);
        $this->assertFileDoesNotExist($testPath);
    }

    public function test_gallery_album_upload_creates_unified_media_item(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Girls in Tech 2025',
            'slug' => 'girls-in-tech-2025',
            'is_active' => true,
        ]);

        // Create dummy image file for upload
        $image = UploadedFile::fake()->image('girls_tech_test.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.photos.upload', $album), [
            'photos' => [$image],
            'caption' => 'Coding workshop participants',
            'year' => 2025,
        ]);

        $response->assertRedirect(route('admin.gallery.photos', $album));

        // Check GalleryPhoto created
        $photo = GalleryPhoto::where('gallery_album_id', $album->id)->first();
        $this->assertNotNull($photo);
        $this->assertEquals('Coding workshop participants', $photo->caption);
        $this->assertEquals(2025, $photo->year);

        // Check MediaItem created with same link
        $mediaItem = MediaItem::where('gallery_photo_id', $photo->id)->first();
        $this->assertNotNull($mediaItem);
        $this->assertEquals($album->id, $mediaItem->gallery_album_id);
        $this->assertEquals(2025, $mediaItem->year);
        $this->assertEquals('gallery', $mediaItem->source_type);
        $this->assertTrue((bool) $mediaItem->is_public);

        // Clean up test upload
        if (File::exists(public_path($photo->image_path))) {
            @unlink(public_path($photo->image_path));
        }
    }
}
