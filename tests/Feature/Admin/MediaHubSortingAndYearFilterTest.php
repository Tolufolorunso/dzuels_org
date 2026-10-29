<?php

namespace Tests\Feature\Admin;

use App\Models\MediaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaHubSortingAndYearFilterTest extends TestCase
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

    public function test_admin_can_filter_media_library_by_year(): void
    {
        $item2024 = MediaItem::create([
            'filename' => 'techday_2024_01.webp',
            'file_path' => 'uploads/media/techday_2024_01.webp',
            'file_size' => 120000,
            'mime_type' => 'image/webp',
            'width' => 1200,
            'height' => 800,
            'title' => 'Tech Day 2024 Photo',
            'source_type' => 'gallery',
            'year' => 2024,
        ]);

        $item2025 = MediaItem::create([
            'filename' => 'techday_2025_01.webp',
            'file_path' => 'uploads/media/techday_2025_01.webp',
            'file_size' => 130000,
            'mime_type' => 'image/webp',
            'width' => 1200,
            'height' => 800,
            'title' => 'Tech Day 2025 Photo',
            'source_type' => 'gallery',
            'year' => 2025,
        ]);

        $item2026 = MediaItem::create([
            'filename' => 'techday_2026_01.webp',
            'file_path' => 'uploads/media/techday_2026_01.webp',
            'file_size' => 135000,
            'mime_type' => 'image/webp',
            'width' => 1200,
            'height' => 800,
            'title' => 'Tech Day 2026 Photo',
            'source_type' => 'gallery',
            'year' => 2026,
        ]);

        // Filter for 2025
        $response = $this->actingAs($this->admin)->get('/admin/media?year=2025');
        $response->assertOk();
        $response->assertSee('Tech Day 2025 Photo');
        $response->assertDontSee('Tech Day 2024 Photo');
        $response->assertDontSee('Tech Day 2026 Photo');

        // Filter for 2024
        $response2024 = $this->actingAs($this->admin)->get('/admin/media?year=2024');
        $response2024->assertOk();
        $response2024->assertSee('Tech Day 2024 Photo');
        $response2024->assertDontSee('Tech Day 2025 Photo');

        // All years
        $responseAll = $this->actingAs($this->admin)->get('/admin/media?year=all');
        $responseAll->assertOk();
        $responseAll->assertSee('Tech Day 2024 Photo');
        $responseAll->assertSee('Tech Day 2025 Photo');
        $responseAll->assertSee('Tech Day 2026 Photo');
    }

    public function test_admin_can_sort_media_by_year_descending_and_ascending(): void
    {
        $itemOld = MediaItem::create([
            'filename' => 'event_2024.webp',
            'file_path' => 'uploads/media/event_2024.webp',
            'file_size' => 110000,
            'mime_type' => 'image/webp',
            'title' => 'Event 2024',
            'source_type' => 'gallery',
            'year' => 2024,
        ]);

        $itemNew = MediaItem::create([
            'filename' => 'event_2026.webp',
            'file_path' => 'uploads/media/event_2026.webp',
            'file_size' => 115000,
            'mime_type' => 'image/webp',
            'title' => 'Event 2026',
            'source_type' => 'gallery',
            'year' => 2026,
        ]);

        $responseDesc = $this->actingAs($this->admin)->get('/admin/media?sort=year_desc');
        $responseDesc->assertOk();
        $viewItemsDesc = $responseDesc->viewData('mediaItems');
        $this->assertEquals(2026, $viewItemsDesc->first()->year);
        $this->assertEquals(2024, $viewItemsDesc->last()->year);

        $responseAsc = $this->actingAs($this->admin)->get('/admin/media?sort=year_asc');
        $responseAsc->assertOk();
        $viewItemsAsc = $responseAsc->viewData('mediaItems');
        $this->assertEquals(2024, $viewItemsAsc->first()->year);
        $this->assertEquals(2026, $viewItemsAsc->last()->year);
    }

    public function test_picker_items_api_supports_year_filtering_and_returns_years_list(): void
    {
        MediaItem::create([
            'filename' => 'reading_2024.webp',
            'file_path' => 'uploads/media/reading_2024.webp',
            'file_size' => 90000,
            'mime_type' => 'image/webp',
            'title' => 'Reading 2024',
            'source_type' => 'gallery',
            'year' => 2024,
        ]);

        MediaItem::create([
            'filename' => 'reading_2025.webp',
            'file_path' => 'uploads/media/reading_2025.webp',
            'file_size' => 95000,
            'mime_type' => 'image/webp',
            'title' => 'Reading 2025',
            'source_type' => 'gallery',
            'year' => 2025,
        ]);

        $response = $this->actingAs($this->admin)->getJson('/admin/media/picker-items?year=2024');

        $response->assertOk();
        $response->assertJsonStructure([
            'items',
            'collections',
            'years',
        ]);

        $items = $response->json('items');
        $this->assertCount(1, $items);
        $this->assertEquals('Reading 2024', $items[0]['title']);
        $this->assertEquals(2024, $items[0]['year']);

        $years = $response->json('years');
        $this->assertContains(2024, $years);
        $this->assertContains(2025, $years);
    }

    public function test_picker_items_api_supports_year_sorting(): void
    {
        MediaItem::create([
            'filename' => 'photo_2024.webp',
            'file_path' => 'uploads/media/photo_2024.webp',
            'file_size' => 90000,
            'mime_type' => 'image/webp',
            'title' => 'Photo 2024',
            'source_type' => 'gallery',
            'year' => 2024,
        ]);

        MediaItem::create([
            'filename' => 'photo_2026.webp',
            'file_path' => 'uploads/media/photo_2026.webp',
            'file_size' => 95000,
            'mime_type' => 'image/webp',
            'title' => 'Photo 2026',
            'source_type' => 'gallery',
            'year' => 2026,
        ]);

        $response = $this->actingAs($this->admin)->getJson('/admin/media/picker-items?sort=year_desc');
        $response->assertOk();
        $items = $response->json('items');
        $this->assertEquals(2026, $items[0]['year']);
        $this->assertEquals(2024, $items[1]['year']);
    }

    public function test_media_item_can_be_updated_with_year(): void
    {
        $item = MediaItem::create([
            'filename' => 'test_item.webp',
            'file_path' => 'uploads/media/test_item.webp',
            'file_size' => 80000,
            'mime_type' => 'image/webp',
            'title' => 'Initial Title',
            'source_type' => 'general',
            'year' => null,
        ]);

        $response = $this->actingAs($this->admin)->putJson("/admin/media/{$item->id}", [
            'title' => 'Updated Title',
            'year' => 2025,
        ]);

        $response->assertOk();
        $this->assertEquals(2025, $item->fresh()->year);
        $this->assertEquals('Updated Title', $item->fresh()->title);
    }
}
