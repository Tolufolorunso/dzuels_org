<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryAlbum;
use App\Models\MediaItem;
use App\Models\User;
use App\Services\ImageOptimizerService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
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

    public function test_guest_cannot_access_media_library_routes(): void
    {
        $response = $this->get('/admin/media');
        $response->assertRedirect('/admin/login');

        $response = $this->get('/admin/media/picker-items');
        $response->assertRedirect('/admin/login');

        $response = $this->post('/admin/media/upload', []);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_media_library_index_with_metrics(): void
    {
        MediaItem::create([
            'filename' => 'workshop_01.webp',
            'file_path' => 'uploads/media/workshop_01.webp',
            'file_size' => 140000,
            'mime_type' => 'image/webp',
            'width' => 1200,
            'height' => 800,
            'title' => 'Workshop Photo 01',
            'source_type' => 'gallery',
        ]);

        MediaItem::create([
            'filename' => 'campus_outreach.webp',
            'file_path' => 'uploads/media/campus_outreach.webp',
            'file_size' => 120000,
            'mime_type' => 'image/webp',
            'width' => 1600,
            'height' => 1066,
            'title' => 'Campus Outreach',
            'source_type' => 'cms',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/media');

        $response->assertOk();
        $response->assertViewIs('admin.media.index');
        $response->assertSee('Central Media Library');
        $response->assertSee('Total Cataloged');
        $response->assertSee('Workshop Photo 01');
        $response->assertSee('Campus Outreach');
    }

    public function test_admin_can_search_media_by_name_and_caption(): void
    {
        MediaItem::create([
            'filename' => 'robotics_class.webp',
            'file_path' => 'uploads/media/robotics_class.webp',
            'file_size' => 95000,
            'mime_type' => 'image/webp',
            'title' => 'Robotics Coding Class',
            'caption' => 'Students learning python robotics',
            'source_type' => 'general',
        ]);

        MediaItem::create([
            'filename' => 'food_distribution.webp',
            'file_path' => 'uploads/media/food_distribution.webp',
            'file_size' => 110000,
            'mime_type' => 'image/webp',
            'title' => 'Food Relief Outreach',
            'caption' => 'Distributing packages to families',
            'source_type' => 'general',
        ]);

        $searchRobotics = $this->actingAs($this->admin)->get('/admin/media?search=Robotics');
        $searchRobotics->assertOk();
        $searchRobotics->assertSee('Robotics Coding Class');
        $searchRobotics->assertDontSee('Food Relief Outreach');

        $searchCaption = $this->actingAs($this->admin)->get('/admin/media?search=families');
        $searchCaption->assertOk();
        $searchCaption->assertSee('Food Relief Outreach');
        $searchCaption->assertDontSee('Robotics Coding Class');
    }

    public function test_admin_can_filter_media_by_date(): void
    {
        $todayItem = MediaItem::create([
            'filename' => 'today_photo.webp',
            'file_path' => 'uploads/media/today_photo.webp',
            'file_size' => 80000,
            'mime_type' => 'image/webp',
            'title' => 'Today Fresh Photo',
        ]);

        $oldItem = MediaItem::create([
            'filename' => 'old_photo.webp',
            'file_path' => 'uploads/media/old_photo.webp',
            'file_size' => 80000,
            'mime_type' => 'image/webp',
            'title' => 'Old Archived Photo',
        ]);
        $oldItem->created_at = Carbon::now()->subMonths(6);
        $oldItem->save();

        $responseToday = $this->actingAs($this->admin)->get('/admin/media?date_filter=today');
        $responseToday->assertOk();
        $responseToday->assertSee('Today Fresh Photo');
        $responseToday->assertDontSee('Old Archived Photo');
    }

    public function test_admin_can_filter_media_by_category(): void
    {
        MediaItem::create([
            'filename' => 'gallery_photo.webp',
            'file_path' => 'uploads/gallery/gallery_photo.webp',
            'file_size' => 75000,
            'mime_type' => 'image/webp',
            'title' => 'Annual Gala Gallery',
            'source_type' => 'gallery',
        ]);

        MediaItem::create([
            'filename' => 'site_banner.webp',
            'file_path' => 'front/images/site_banner.webp',
            'file_size' => 90000,
            'mime_type' => 'image/webp',
            'title' => 'Homepage Hero Banner',
            'source_type' => 'cms',
        ]);

        $responseGallery = $this->actingAs($this->admin)->get('/admin/media?category=gallery');
        $responseGallery->assertOk();
        $responseGallery->assertSee('Annual Gala Gallery');
        $responseGallery->assertDontSee('Homepage Hero Banner');

        $responseSite = $this->actingAs($this->admin)->get('/admin/media?category=site');
        $responseSite->assertOk();
        $responseSite->assertSee('Homepage Hero Banner');
        $responseSite->assertDontSee('Annual Gala Gallery');
    }

    public function test_admin_can_upload_and_optimize_image_into_media_library(): void
    {
        $optimizerMock = $this->mock(ImageOptimizerService::class);
        $optimizerMock->shouldReceive('optimize')
            ->once()
            ->andReturn('uploads/media/mocked_optimized.webp');

        $file = UploadedFile::fake()->image('test_award.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->postJson('/admin/media/upload', [
            'image' => $file,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'item' => [
                'filename' => 'mocked_optimized.webp',
                'file_path' => 'uploads/media/mocked_optimized.webp',
            ],
        ]);

        $this->assertDatabaseHas('media_items', [
            'filename' => 'mocked_optimized.webp',
            'file_path' => 'uploads/media/mocked_optimized.webp',
            'mime_type' => 'image/webp',
        ]);
    }

    public function test_admin_can_update_media_metadata(): void
    {
        $item = MediaItem::create([
            'filename' => 'event.webp',
            'file_path' => 'uploads/media/event.webp',
            'file_size' => 60000,
            'mime_type' => 'image/webp',
            'title' => 'Original Title',
            'caption' => 'Original Caption',
            'alt_text' => 'Original Alt',
        ]);

        $response = $this->actingAs($this->admin)->putJson("/admin/media/{$item->id}", [
            'title' => 'Updated Community Event',
            'caption' => 'Gathering of foundation partners in Ibadan',
            'alt_text' => 'Partners smiling at conference',
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('media_items', [
            'id' => $item->id,
            'title' => 'Updated Community Event',
            'caption' => 'Gathering of foundation partners in Ibadan',
            'alt_text' => 'Partners smiling at conference',
        ]);
    }

    public function test_admin_can_delete_media_item(): void
    {
        $item = MediaItem::create([
            'filename' => 'deletable.webp',
            'file_path' => 'uploads/media/deletable.webp',
            'file_size' => 50000,
            'mime_type' => 'image/webp',
            'title' => 'To Be Deleted',
        ]);

        $response = $this->actingAs($this->admin)->deleteJson("/admin/media/{$item->id}");

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('media_items', [
            'id' => $item->id,
        ]);
    }

    public function test_admin_can_bulk_delete_media_items(): void
    {
        $item1 = MediaItem::create([
            'filename' => 'bulk_01.webp',
            'file_path' => 'uploads/media/bulk_01.webp',
            'file_size' => 40000,
            'mime_type' => 'image/webp',
            'title' => 'Bulk Delete 1',
        ]);

        $item2 = MediaItem::create([
            'filename' => 'bulk_02.webp',
            'file_path' => 'uploads/media/bulk_02.webp',
            'file_size' => 45000,
            'mime_type' => 'image/webp',
            'title' => 'Bulk Delete 2',
        ]);

        $item3 = MediaItem::create([
            'filename' => 'bulk_keep.webp',
            'file_path' => 'uploads/media/bulk_keep.webp',
            'file_size' => 50000,
            'mime_type' => 'image/webp',
            'title' => 'Bulk Keep',
        ]);

        $response = $this->actingAs($this->admin)->postJson('/admin/media/bulk-delete', [
            'ids' => [$item1->id, $item2->id],
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('media_items', ['id' => $item1->id]);
        $this->assertDatabaseMissing('media_items', ['id' => $item2->id]);
        $this->assertDatabaseHas('media_items', ['id' => $item3->id]);
    }

    public function test_picker_items_endpoint_returns_json_with_filters(): void
    {
        MediaItem::create([
            'filename' => 'maths_quiz.webp',
            'file_path' => 'uploads/media/maths_quiz.webp',
            'file_size' => 70000,
            'mime_type' => 'image/webp',
            'title' => 'Mathematics Competition 2026',
            'source_type' => 'general',
        ]);

        MediaItem::create([
            'filename' => 'dance_troupe.webp',
            'file_path' => 'uploads/media/dance_troupe.webp',
            'file_size' => 85000,
            'mime_type' => 'image/webp',
            'title' => 'Cultural Dance Troupe',
            'source_type' => 'gallery',
        ]);

        $response = $this->actingAs($this->admin)->getJson('/admin/media/picker-items?search=Mathematics');

        $response->assertOk();
        $response->assertJsonStructure([
            'items' => [
                '*' => [
                    'id',
                    'filename',
                    'file_path',
                    'image_url',
                    'title',
                    'caption',
                    'alt_text',
                    'formatted_size',
                    'dimensions',
                    'date_formatted',
                    'source_type',
                ],
            ],
        ]);

        $response->assertJsonCount(1, 'items');
        $response->assertJsonPath('items.0.title', 'Mathematics Competition 2026');
    }

    public function test_admin_can_create_new_collection_and_dynamic_album(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/admin/media/collections', [
            'title' => 'Essay Competition 2026',
            'kicker' => 'Academic Excellence',
            'description' => 'Annual student essay competition in Ijero-Ekiti.',
            'is_public' => true,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('collection.title', 'Essay Competition 2026');
        $response->assertJsonPath('collection.slug', 'essay-competition-2026');
        $response->assertJsonPath('collection.is_public', true);

        $this->assertDatabaseHas('gallery_albums', [
            'title' => 'Essay Competition 2026',
            'slug' => 'essay-competition-2026',
            'is_public' => true,
        ]);
    }

    public function test_admin_can_create_internal_only_collection(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/media/collections', [
            'title' => 'Staff Offsite Archive',
            'is_public' => false,
        ]);

        $response->assertRedirect('/admin/media?category=staff-offsite-archive');

        $this->assertDatabaseHas('gallery_albums', [
            'title' => 'Staff Offsite Archive',
            'is_public' => false,
        ]);
    }

    public function test_media_item_can_be_assigned_to_collection_and_filtered_by_slug(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'WDADA Community Outreach',
            'slug' => 'wdada-community-outreach',
            'kicker' => 'Community',
            'is_active' => true,
            'is_public' => true,
            'sort_order' => 1,
        ]);

        $item1 = MediaItem::create([
            'filename' => 'wdada_01.webp',
            'file_path' => 'uploads/media/wdada_01.webp',
            'file_size' => 110000,
            'mime_type' => 'image/webp',
            'title' => 'WDADA Event Photo',
            'source_type' => 'gallery',
            'gallery_album_id' => $album->id,
        ]);

        $item2 = MediaItem::create([
            'filename' => 'other_01.webp',
            'file_path' => 'uploads/media/other_01.webp',
            'file_size' => 90000,
            'mime_type' => 'image/webp',
            'title' => 'General Photo',
            'source_type' => 'general',
            'gallery_album_id' => null,
        ]);

        // Filter by dynamic slug
        $response = $this->actingAs($this->admin)->get('/admin/media?category=wdada-community-outreach');
        $response->assertOk();
        $response->assertSee('WDADA Event Photo');
        $response->assertDontSee('other_01.webp');

        // Test picker returns collections
        $pickerResponse = $this->actingAs($this->admin)->getJson('/admin/media/picker-items');
        $pickerResponse->assertOk();
        $pickerResponse->assertJsonStructure([
            'items',
            'collections' => [
                '*' => ['id', 'title', 'slug', 'is_public'],
            ],
        ]);
    }
}
