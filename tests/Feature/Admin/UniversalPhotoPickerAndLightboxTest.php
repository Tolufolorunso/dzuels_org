<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\MediaItem;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UniversalPhotoPickerAndLightboxTest extends TestCase
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
            'filename' => "hub_image_{$counter}.webp",
            'file_path' => "uploads/media/hub_image_{$counter}.webp",
            'file_size' => 120000,
            'mime_type' => 'image/webp',
            'width' => 1920,
            'height' => 1080,
            'title' => "Hub Image {$counter}",
            'source_type' => 'general',
            'is_public' => true,
            'year' => 2025,
        ], $attributes));
    }

    public function test_admin_can_view_album_photos_page_with_library_picker_as_default(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Digital Skills Workshop',
            'slug' => 'digital-skills-workshop',
            'description' => 'Training rural youth',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.gallery.photos', $album));

        $response->assertOk();
        $response->assertSee('1. Pick from Central Media Library (Default)');
        $response->assertSee('2. Upload New Photos (Direct Ingestion)');
        $response->assertSee('Choose Photos from Central Media Hub');
    }

    public function test_admin_can_attach_media_items_to_gallery_album_without_reuploading(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Reading Challenge 2025',
            'slug' => 'reading-challenge-2025',
            'description' => 'Annual reading challenge',
            'is_active' => true,
        ]);

        $item1 = $this->createMediaItem(['title' => 'Student Reading 1', 'caption' => 'First student reading']);
        $item2 = $this->createMediaItem(['title' => 'Student Reading 2', 'caption' => 'Second student reading']);

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.photos.attach-media', $album), [
            'media_item_ids' => [$item1->id, $item2->id],
        ]);

        $response->assertRedirect(route('admin.gallery.photos', $album));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('gallery_photos', [
            'gallery_album_id' => $album->id,
            'image_path' => $item1->file_path,
            'caption' => 'First student reading',
        ]);

        $this->assertDatabaseHas('gallery_photos', [
            'gallery_album_id' => $album->id,
            'image_path' => $item2->file_path,
            'caption' => 'Second student reading',
        ]);

        $this->assertEquals($album->id, $item1->fresh()->gallery_album_id);
        $this->assertEquals($album->id, $item2->fresh()->gallery_album_id);
    }

    public function test_admin_can_create_and_update_event_with_gallery_photos_and_linked_album(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Ijero Tech Day 2026 Archives',
            'slug' => 'itd-2026',
            'is_active' => true,
        ]);

        $galleryPhotosPayload = [
            [
                'path' => 'uploads/events/photo1.webp',
                'caption' => 'Keynote Presentation',
                'alt_text' => 'Keynote speaker',
                'media_item_id' => null,
            ],
            [
                'path' => 'uploads/events/photo2.webp',
                'caption' => 'Coding Competition',
                'alt_text' => 'Students competing',
                'media_item_id' => null,
            ],
        ];

        $createResponse = $this->actingAs($this->admin)->post(route('admin.events.store'), [
            'title' => 'Ijero Tech Day 2026',
            'summary' => 'Premier annual tech festival',
            'description' => 'Comprehensive event description celebrating rural innovation.',
            'event_date' => '2026-10-17 09:00:00',
            'gallery_album_id' => $album->id,
            'gallery_photos' => json_encode($galleryPhotosPayload),
        ]);

        $createResponse->assertRedirect(route('admin.events.index'));

        $event = Event::where('slug', 'ijero-tech-day-2026')->first();
        $this->assertNotNull($event);
        $this->assertEquals($album->id, $event->gallery_album_id);
        $this->assertCount(2, $event->gallery_photos);
        $this->assertEquals('Keynote Presentation', $event->gallery_photos[0]['caption']);

        // Test update
        $updatedPhotos = [
            [
                'path' => 'uploads/events/photo3.webp',
                'caption' => 'Award Ceremony',
                'alt_text' => 'Winners trophy',
                'media_item_id' => null,
            ],
        ];

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.events.update', $event), [
            'title' => 'Ijero Tech Day 2026 (Updated)',
            'summary' => 'Updated summary teaser',
            'description' => 'Updated full description.',
            'gallery_album_id' => $album->id,
            'gallery_photos' => json_encode($updatedPhotos),
        ]);

        $updateResponse->assertRedirect(route('admin.events.index'));
        $event->refresh();
        $this->assertEquals('Ijero Tech Day 2026 (Updated)', $event->title);
        $this->assertCount(1, $event->gallery_photos);
        $this->assertEquals('Award Ceremony', $event->gallery_photos[0]['caption']);
    }

    public function test_admin_can_create_and_update_programme_with_gallery_photos_and_linked_album(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Famoyinka Library Collection',
            'slug' => 'famoyinka-library',
            'is_active' => true,
        ]);

        $photosPayload = [
            [
                'path' => 'uploads/programmes/reading_circle.webp',
                'caption' => 'Weekend Reading Circle',
                'alt_text' => 'Children reading books',
                'media_item_id' => null,
            ],
        ];

        $createResponse = $this->actingAs($this->admin)->post(route('admin.programmes.store'), [
            'title' => 'Famoyinka Memorial Children Library',
            'summary' => 'Free public library providing quality books',
            'body' => 'Comprehensive programme narrative highlighting literacy outreach.',
            'gallery_album_id' => $album->id,
            'gallery_photos' => json_encode($photosPayload),
        ]);

        $createResponse->assertRedirect(route('admin.programmes.index'));

        $programme = Programme::where('slug', 'famoyinka-memorial-children-library')->first();
        $this->assertNotNull($programme);
        $this->assertEquals($album->id, $programme->gallery_album_id);
        $this->assertCount(1, $programme->gallery_photos);

        // Test update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.programmes.update', $programme), [
            'title' => 'Famoyinka Memorial Children Library',
            'summary' => 'Updated summary description',
            'body' => 'Updated programme body text.',
            'gallery_album_id' => $album->id,
            'gallery_photos' => json_encode([]),
        ]);

        $updateResponse->assertRedirect(route('admin.programmes.index'));
        $programme->refresh();
        $this->assertCount(0, $programme->gallery_photos);
    }

    public function test_public_event_show_page_renders_attached_gallery_photos_and_lightbox_triggers(): void
    {
        $event = Event::create([
            'title' => 'Ekiti Youth Hackathon 2026',
            'slug' => 'ekiti-youth-hackathon-2026',
            'summary' => 'Coding hackathon in Ijero',
            'description' => 'Intense 24-hour coding challenge.',
            'event_date' => '2026-11-20 10:00:00',
            'is_active' => true,
            'gallery_photos' => [
                [
                    'path' => 'front/images/gallery/optimized/hackathon-01.webp',
                    'caption' => 'Student teams presenting prototype',
                    'alt_text' => 'Student presentation',
                ],
            ],
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertOk();
        $response->assertSee('photo-gallery');
        $response->assertSee('Student teams presenting prototype');
        $response->assertSee('lightboxOpen');
        $response->assertSee('openLightbox');
    }

    public function test_public_programme_show_page_renders_attached_gallery_photos_and_lightbox_triggers(): void
    {
        $programme = Programme::create([
            'title' => 'Digital Literacy Outreach',
            'slug' => 'digital-literacy-outreach',
            'summary' => 'Empowering children with computer skills',
            'body' => 'Equipping our own computer lab in Ijero-Ekiti.',
            'is_active' => true,
            'gallery_photos' => [
                [
                    'path' => 'front/images/gallery/optimized/digital-01.webp',
                    'caption' => 'Children learning typing',
                    'alt_text' => 'Computer lab session',
                ],
            ],
        ]);

        $response = $this->get(route('programmes.show', $programme->slug));

        $response->assertOk();
        $response->assertSee('Visual Archive');
        $response->assertSee('Children learning typing');
        $response->assertSee('lightboxOpen');
        $response->assertSee('openLightbox');
    }

    public function test_event_and_programme_can_direct_upload_new_gallery_photos(): void
    {
        $file = UploadedFile::fake()->image('direct_upload_event.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.events.store'), [
            'title' => 'Robotics Bootcamp',
            'summary' => 'High school robotics training',
            'description' => 'Students building line-following robots.',
            'event_date' => '2026-12-05 09:00:00',
            'new_gallery_photos' => [$file],
        ]);

        $response->assertRedirect(route('admin.events.index'));

        $event = Event::where('slug', 'robotics-bootcamp')->first();
        $this->assertNotNull($event);
        $this->assertNotEmpty($event->gallery_photos);

        // Check MediaItem was automatically registered
        $this->assertDatabaseHas('media_items', [
            'source_type' => 'event',
            'alt_text' => 'Robotics Bootcamp',
        ]);
    }
}
