<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use App\Models\PageSection;
use App\Models\Programme;
use App\Models\User;
use Database\Seeders\PageSectionSeeder;
use Database\Seeders\ProgrammeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaVisibilityAndPickerOverhaulTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PageSectionSeeder::class);
        $this->seed(ProgrammeSeeder::class);

        $this->admin = User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    /**
     * Test brand correction replaces "Innovation and Tech Day" with "Ijero Tech Day".
     */
    public function test_brand_correction_replaces_innovation_and_tech_day_with_ijero_tech_day(): void
    {
        // 1. Check footer & layout on homepage
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ijero Tech Day');
        $response->assertDontSee('Innovation & Tech Day');
        $response->assertDontSee('Innovation and Tech Day');

        // 2. Check legacy programme slug 301 redirect
        $redirectResponse = $this->get('/programmes/innovation-and-tech-day');
        $redirectResponse->assertStatus(301);
        $redirectResponse->assertRedirect('/events/ijero-tech-day-2026');

        // 3. Check programme database title
        $programme = Programme::where('slug', 'innovation-and-tech-day')->first();
        $this->assertNotNull($programme);
        $this->assertEquals('Ijero Tech Day (ITD)', $programme->title);
    }

    /**
     * Test gallery photos and media items support is_public column and Eloquent scopes.
     */
    public function test_gallery_photos_and_media_items_support_is_public_visibility(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Test Visibility Album',
            'slug' => 'test-visibility-album',
            'is_active' => true,
            'is_public' => true,
            'sort_order' => 1,
        ]);

        $publicPhoto = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'front/images/test_pub.webp',
            'caption' => 'Public Photo',
            'alt_text' => 'Public Alt',
            'is_public' => true,
            'sort_order' => 1,
        ]);

        $archivePhoto = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'front/images/test_arc.webp',
            'caption' => 'Archive Photo',
            'alt_text' => 'Archive Alt',
            'is_public' => false,
            'sort_order' => 2,
        ]);

        $this->assertTrue(GalleryPhoto::public()->where('id', $publicPhoto->id)->exists());
        $this->assertFalse(GalleryPhoto::public()->where('id', $archivePhoto->id)->exists());
        $this->assertTrue(GalleryPhoto::archive()->where('id', $archivePhoto->id)->exists());

        $publicMedia = MediaItem::create([
            'filename' => 'media_pub.webp',
            'file_path' => 'uploads/media_pub.webp',
            'file_size' => 120000,
            'mime_type' => 'image/webp',
            'title' => 'Public Media',
            'source_type' => 'gallery',
            'gallery_album_id' => $album->id,
            'gallery_photo_id' => $publicPhoto->id,
            'is_public' => true,
        ]);

        $archiveMedia = MediaItem::create([
            'filename' => 'media_arc.webp',
            'file_path' => 'uploads/media_arc.webp',
            'file_size' => 130000,
            'mime_type' => 'image/webp',
            'title' => 'Archive Media',
            'source_type' => 'gallery',
            'gallery_album_id' => $album->id,
            'gallery_photo_id' => $archivePhoto->id,
            'is_public' => false,
        ]);

        $this->assertTrue(MediaItem::public()->where('id', $publicMedia->id)->exists());
        $this->assertFalse(MediaItem::public()->where('id', $archiveMedia->id)->exists());
        $this->assertTrue(MediaItem::archive()->where('id', $archiveMedia->id)->exists());
        $this->assertEquals(1, MediaItem::byVisibility('public')->whereIn('id', [$publicMedia->id, $archiveMedia->id])->count());
        $this->assertEquals(1, MediaItem::byVisibility('archive')->whereIn('id', [$publicMedia->id, $archiveMedia->id])->count());
    }

    /**
     * Test public gallery strictly omits photos marked as is_public = false.
     */
    public function test_public_gallery_strictly_omits_archived_photos(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Curated Public Album',
            'slug' => 'curated-public-album',
            'is_active' => true,
            'is_public' => true,
            'sort_order' => 1,
        ]);

        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'front/images/visible_highlight.webp',
            'caption' => 'Visible Highlight Caption',
            'alt_text' => 'Visible Highlight Alt',
            'is_public' => true,
            'sort_order' => 1,
        ]);

        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'front/images/hidden_archive.webp',
            'caption' => 'Secret Internal Archive Caption',
            'alt_text' => 'Secret Internal Archive Alt',
            'is_public' => false,
            'sort_order' => 2,
        ]);

        // Gallery index
        $indexResponse = $this->get('/gallery');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Visible Highlight Caption');
        $indexResponse->assertDontSee('Secret Internal Archive Caption');

        // Gallery album show page
        $albumResponse = $this->get('/gallery/curated-public-album');
        $albumResponse->assertStatus(200);
        $albumResponse->assertSee('Visible Highlight Caption');
        $albumResponse->assertDontSee('Secret Internal Archive Caption');
    }

    /**
     * Test admin can toggle media visibility with single-click and bulk actions.
     */
    public function test_admin_can_toggle_media_visibility_with_single_click_and_bulk_action(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Toggle Test Album',
            'slug' => 'toggle-test-album',
            'is_active' => true,
            'is_public' => true,
            'sort_order' => 1,
        ]);

        $photo = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'front/images/toggle_target.webp',
            'caption' => 'Toggle Target',
            'is_public' => true,
            'sort_order' => 1,
        ]);

        $mediaItem = MediaItem::create([
            'filename' => 'toggle_target.webp',
            'file_path' => 'front/images/toggle_target.webp',
            'file_size' => 110000,
            'mime_type' => 'image/webp',
            'title' => 'Toggle Target',
            'source_type' => 'gallery',
            'gallery_album_id' => $album->id,
            'gallery_photo_id' => $photo->id,
            'is_public' => true,
        ]);

        // 1. Single click toggle via JSON API
        $toggleResponse = $this->actingAs($this->admin)->postJson(route('admin.media.toggle-visibility', $mediaItem));
        $toggleResponse->assertStatus(200);
        $toggleResponse->assertJson([
            'success' => true,
            'is_public' => false,
        ]);

        $mediaItem->refresh();
        $photo->refresh();
        $this->assertFalse($mediaItem->is_public);
        $this->assertFalse($photo->is_public);

        // 2. Toggle back to public
        $toggleBackResponse = $this->actingAs($this->admin)->postJson(route('admin.media.toggle-visibility', $mediaItem));
        $toggleBackResponse->assertStatus(200);
        $toggleBackResponse->assertJson([
            'success' => true,
            'is_public' => true,
        ]);

        $mediaItem->refresh();
        $photo->refresh();
        $this->assertTrue($mediaItem->is_public);
        $this->assertTrue($photo->is_public);

        // 3. Bulk visibility action
        $item2 = MediaItem::create([
            'filename' => 'toggle_target_2.webp',
            'file_path' => 'front/images/toggle_target_2.webp',
            'file_size' => 115000,
            'mime_type' => 'image/webp',
            'title' => 'Toggle Target 2',
            'source_type' => 'general',
            'is_public' => true,
        ]);

        $bulkResponse = $this->actingAs($this->admin)->postJson(route('admin.media.bulk-visibility'), [
            'ids' => [$mediaItem->id, $item2->id],
            'is_public' => false,
        ]);

        $bulkResponse->assertStatus(200);
        $bulkResponse->assertJson([
            'success' => true,
            'count' => 2,
            'is_public' => false,
        ]);

        $this->assertFalse($mediaItem->fresh()->is_public);
        $this->assertFalse($item2->fresh()->is_public);
        $this->assertFalse($photo->fresh()->is_public);
    }

    /**
     * Test universal media picker supports pagination beyond the 80-item cutoff and includes usage indicators.
     */
    public function test_media_picker_api_returns_pagination_without_80_item_cutoff_and_includes_usage_indicators(): void
    {
        // Create 90 items first
        for ($i = 1; $i <= 90; $i++) {
            MediaItem::create([
                'filename' => "sample_batch_photo_{$i}.webp",
                'file_path' => "uploads/media/sample_batch_photo_{$i}.webp",
                'file_size' => 100000 + $i,
                'mime_type' => 'image/webp',
                'title' => "Batch Photo {$i}",
                'source_type' => 'general',
                'is_public' => $i % 2 === 0,
            ]);
        }

        // Reference one photo on the site to trigger "Used on Site" (created last so it is on page 1 of newest first)
        PageSection::create([
            'page_key' => 'home',
            'section_key' => 'test_feature_hero',
            'headline' => 'Test Headline',
            'image_path' => 'uploads/media/featured_hero_photo.webp',
            'sort_order' => 1,
        ]);

        $siteUsedItem = MediaItem::create([
            'filename' => 'featured_hero_photo.webp',
            'file_path' => 'uploads/media/featured_hero_photo.webp',
            'file_size' => 125000,
            'mime_type' => 'image/webp',
            'title' => 'Featured Hero Photo',
            'source_type' => 'cms',
            'is_public' => true,
        ]);

        // Page 1 with per_page=60
        $page1Response = $this->actingAs($this->admin)->getJson(route('admin.media.picker-items', [
            'per_page' => 60,
            'page' => 1,
        ]));

        $page1Response->assertStatus(200);
        $page1Response->assertJsonStructure([
            'items',
            'total',
            'current_page',
            'last_page',
            'has_more',
            'collections',
            'years',
        ]);

        $data1 = $page1Response->json();
        $this->assertEquals(91, $data1['total']);
        $this->assertEquals(1, $data1['current_page']);
        $this->assertEquals(2, $data1['last_page']);
        $this->assertTrue($data1['has_more']);
        $this->assertCount(60, $data1['items']);

        // Check usage indicators in item payload
        $foundSiteUsed = false;
        foreach ($data1['items'] as $item) {
            $this->assertArrayHasKey('is_public', $item);
            $this->assertArrayHasKey('is_used_on_site', $item);
            $this->assertArrayHasKey('is_displayed_in_gallery', $item);

            if ($item['id'] === $siteUsedItem->id) {
                $this->assertTrue($item['is_used_on_site']);
                $foundSiteUsed = true;
            }
        }
        $this->assertTrue($foundSiteUsed, 'Item referenced in PageSection must have is_used_on_site = true');

        // Page 2
        $page2Response = $this->actingAs($this->admin)->getJson(route('admin.media.picker-items', [
            'per_page' => 60,
            'page' => 2,
        ]));

        $page2Response->assertStatus(200);
        $data2 = $page2Response->json();
        $this->assertEquals(2, $data2['current_page']);
        $this->assertFalse($data2['has_more']);
        $this->assertCount(31, $data2['items']);
    }

    /**
     * Test admin media index view renders visibility filters and indicators.
     */
    public function test_admin_media_index_can_filter_by_visibility(): void
    {
        MediaItem::create([
            'filename' => 'index_pub.webp',
            'file_path' => 'uploads/index_pub.webp',
            'file_size' => 110000,
            'mime_type' => 'image/webp',
            'title' => 'Index Public Visible',
            'source_type' => 'gallery',
            'is_public' => true,
        ]);

        MediaItem::create([
            'filename' => 'index_arc.webp',
            'file_path' => 'uploads/index_arc.webp',
            'file_size' => 115000,
            'mime_type' => 'image/webp',
            'title' => 'Index Archive Hidden',
            'source_type' => 'general',
            'is_public' => false,
        ]);

        // Filter public
        $pubResponse = $this->actingAs($this->admin)->get(route('admin.media.index', ['visibility' => 'public']));
        $pubResponse->assertStatus(200);
        $pubResponse->assertSee('Index Public Visible');
        $pubResponse->assertDontSee('Index Archive Hidden');

        // Filter archive
        $arcResponse = $this->actingAs($this->admin)->get(route('admin.media.index', ['visibility' => 'archive']));
        $arcResponse->assertStatus(200);
        $arcResponse->assertSee('Index Archive Hidden');
        $arcResponse->assertDontSee('Index Public Visible');
    }
}
