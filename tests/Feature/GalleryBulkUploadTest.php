<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GalleryBulkUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected GalleryAlbum $album;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->album = GalleryAlbum::create([
            'title' => 'Test Community Album',
            'slug' => 'test-community-album',
            'kicker' => 'Community 2026',
            'description' => 'Test description',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_bulk_upload_multiple_photos(): void
    {
        $photos = [
            UploadedFile::fake()->image('pic1.jpg', 400, 300),
            UploadedFile::fake()->image('pic2.png', 400, 300),
            UploadedFile::fake()->image('pic3.webp', 400, 300),
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.gallery.photos.upload', $this->album), [
                'photos' => $photos,
                'caption' => 'Bulk uploaded event shots',
                'year' => '2026',
                'alt_text' => 'Community outreach event',
            ]);

        $response->assertRedirect(route('admin.gallery.photos', $this->album));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('gallery_photos', 3);

        $firstPhoto = GalleryPhoto::first();
        $this->assertEquals($this->album->id, $firstPhoto->gallery_album_id);
        $this->assertEquals('Bulk uploaded event shots', $firstPhoto->caption);
        $this->assertEquals('2026', $firstPhoto->year);
        $this->assertEquals('Community outreach event', $firstPhoto->alt_text);

        // Clean up created upload files if any
        foreach (GalleryPhoto::all() as $photo) {
            if ($photo->image_path && file_exists(public_path($photo->image_path))) {
                @unlink(public_path($photo->image_path));
            }
        }
    }

    public function test_super_admin_can_update_photo_metadata(): void
    {
        $photo = $this->album->photos()->create([
            'image_path' => 'uploads/gallery/photos/sample.webp',
            'caption' => 'Initial caption',
            'alt_text' => 'Initial alt',
            'year' => '2024',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.gallery.photos.update', [$this->album, $photo]), [
                'caption' => 'Updated leadership training session',
                'alt_text' => 'Updated alt description',
                'year' => '2025',
                'sort_order' => 5,
            ]);

        $response->assertRedirect(route('admin.gallery.photos', $this->album));
        $response->assertSessionHas('success');

        $photo->refresh();
        $this->assertEquals('Updated leadership training session', $photo->caption);
        $this->assertEquals('Updated alt description', $photo->alt_text);
        $this->assertEquals('2025', $photo->year);
        $this->assertEquals(5, $photo->sort_order);
    }

    public function test_super_admin_can_view_photos_page(): void
    {
        $this->album->photos()->create([
            'image_path' => 'uploads/gallery/photos/sample.webp',
            'caption' => 'View test photo',
            'alt_text' => 'Alt test',
            'year' => '2025',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.gallery.photos', $this->album));

        $response->assertOk();
        $response->assertSee('Manage Photos - Test Community Album');
        $response->assertSee('Upload & Optimize Photos (Bulk Uploader)', false);
        $response->assertSee('View test photo');
    }
}
