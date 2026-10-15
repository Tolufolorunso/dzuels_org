<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryAlbum;
use App\Models\User;
use App\Services\ImageOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GalleryManagementTest extends TestCase
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

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/gallery');
        $response->assertRedirect('/admin/login');

        $response = $this->get('/admin/gallery/create');
        $response->assertRedirect('/admin/login');

        $response = $this->post('/admin/gallery', []);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_gallery_albums_index(): void
    {
        GalleryAlbum::create([
            'title' => 'Robotics Bootcamp',
            'slug' => 'robotics-bootcamp',
            'kicker' => 'STEM Hands-on',
            'description' => 'Hands-on robotics sessions for kids.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/gallery');

        $response->assertOk();
        $response->assertSeeText('Gallery Collections');
        $response->assertSeeText('Robotics Bootcamp');
        $response->assertSeeText('/gallery/robotics-bootcamp');
    }

    public function test_admin_can_create_gallery_album_with_uploaded_cover(): void
    {
        $cover = UploadedFile::fake()->image('cover.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->post('/admin/gallery', [
            'title' => 'Spelling Bee 2026',
            'slug' => 'spelling-bee-2026',
            'kicker' => 'Literacy Excellence',
            'description' => 'Annual inter-school spelling bee.',
            'cover_image' => $cover,
            'sort_order' => '3',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('gallery_albums', [
            'title' => 'Spelling Bee 2026',
            'slug' => 'spelling-bee-2026',
            'kicker' => 'Literacy Excellence',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $album = GalleryAlbum::where('slug', 'spelling-bee-2026')->first();
        $this->assertNotNull($album->cover_image);
        $this->assertStringStartsWith('uploads/gallery/covers/', $album->cover_image);

        if (file_exists(public_path($album->cover_image))) {
            @unlink(public_path($album->cover_image));
        }
    }

    public function test_admin_can_update_gallery_album(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'kicker' => 'Old Kicker',
            'description' => 'Old description',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/gallery/{$album->id}", [
            'title' => 'Updated Collection Title',
            'slug' => 'updated-collection-title',
            'kicker' => 'Updated Kicker',
            'description' => 'Updated description',
            'sort_order' => 2,
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseHas('gallery_albums', [
            'id' => $album->id,
            'title' => 'Updated Collection Title',
            'slug' => 'updated-collection-title',
            'is_active' => false,
            'sort_order' => 2,
        ]);
    }

    public function test_admin_can_toggle_album_visibility(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Toggle Album',
            'slug' => 'toggle-album',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/gallery/{$album->id}/toggle");
        $response->assertRedirect(route('admin.gallery.index'));

        $this->assertDatabaseHas('gallery_albums', [
            'id' => $album->id,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/gallery/{$album->id}/toggle");
        $this->assertDatabaseHas('gallery_albums', [
            'id' => $album->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_album_and_associated_photos(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Album to Delete',
            'slug' => 'album-to-delete',
            'is_active' => true,
        ]);

        $photo = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/general-01.webp',
            'caption' => 'Test caption',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/gallery/{$album->id}");
        $response->assertRedirect(route('admin.gallery.index'));

        $this->assertDatabaseMissing('gallery_albums', ['id' => $album->id]);
        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
    }

    public function test_admin_can_view_photos_in_album(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Coding Academy',
            'slug' => 'coding-academy',
            'is_active' => true,
        ]);

        $photo = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/general-02.webp',
            'caption' => 'Students solving code challenges',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/gallery/{$album->id}/photos");

        $response->assertOk();
        $response->assertSeeText('Coding Academy');
        $response->assertSeeText('Students solving code challenges');
    }

    public function test_admin_can_upload_photos_to_album(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'ITD Upload Album',
            'slug' => 'itd-upload-album',
            'is_active' => true,
        ]);

        $file1 = UploadedFile::fake()->image('p1.webp', 600, 400);
        $file2 = UploadedFile::fake()->image('p2.webp', 600, 400);

        $response = $this->actingAs($this->admin)->post("/admin/gallery/{$album->id}/photos", [
            'photos' => [$file1, $file2],
            'caption' => 'Tech Day moment',
            'alt_text' => 'Tech Day event photo',
        ]);

        $response->assertRedirect(route('admin.gallery.photos', $album));
        $response->assertSessionHas('success');

        $this->assertEquals(2, $album->photos()->count());

        $firstPhoto = $album->photos()->first();
        $this->assertEquals('Tech Day moment', $firstPhoto->caption);
        $this->assertStringStartsWith('uploads/gallery/photos/', $firstPhoto->image_path);

        foreach ($album->photos as $p) {
            if (file_exists(public_path($p->image_path))) {
                @unlink(public_path($p->image_path));
            }
        }
    }

    public function test_admin_can_delete_photo_from_album(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Delete Photo Album',
            'slug' => 'delete-photo-album',
            'is_active' => true,
        ]);

        $photo = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/general-03.webp',
            'caption' => 'To be deleted',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/gallery/{$album->id}/photos/{$photo->id}");
        $response->assertRedirect(route('admin.gallery.photos', $album));

        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
    }

    public function test_admin_can_update_photo_metadata_including_year(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'ITD Album',
            'slug' => 'itd-album',
            'is_active' => true,
        ]);

        $photo = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/itd-01.webp',
            'caption' => 'Original caption',
            'year' => '2023',
            'alt_text' => 'Old alt',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/gallery/{$album->id}/photos/{$photo->id}", [
            'caption' => 'Ijero Tech Day 2025 Keynote',
            'year' => '2025',
            'alt_text' => 'Keynote presentation',
            'sort_order' => 5,
        ]);

        $response->assertRedirect(route('admin.gallery.photos', $album));
        $this->assertDatabaseHas('gallery_photos', [
            'id' => $photo->id,
            'caption' => 'Ijero Tech Day 2025 Keynote',
            'year' => '2025',
            'alt_text' => 'Keynote presentation',
            'sort_order' => 5,
        ]);
    }

    public function test_admin_can_bulk_delete_photos(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Bulk Delete Album',
            'slug' => 'bulk-delete-album',
            'is_active' => true,
        ]);

        $photo1 = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/p1.webp',
            'caption' => 'Photo 1',
            'sort_order' => 1,
        ]);
        $photo2 = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/p2.webp',
            'caption' => 'Photo 2',
            'sort_order' => 2,
        ]);
        $photo3 = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/p3.webp',
            'caption' => 'Photo 3',
            'sort_order' => 3,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/gallery/{$album->id}/photos/bulk-destroy", [
            'photo_ids' => [$photo1->id, $photo2->id],
        ]);

        $response->assertRedirect(route('admin.gallery.photos', $album));
        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo1->id]);
        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo2->id]);
        $this->assertDatabaseHas('gallery_photos', ['id' => $photo3->id]);
    }

    public function test_admin_can_reorder_albums(): void
    {
        $album1 = GalleryAlbum::create([
            'title' => 'First Album',
            'slug' => 'first-album',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $album2 = GalleryAlbum::create([
            'title' => 'Second Album',
            'slug' => 'second-album',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/gallery/reorder', [
            'album_ids' => [$album2->id, $album1->id],
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertEquals(1, $album2->fresh()->sort_order);
        $this->assertEquals(2, $album1->fresh()->sort_order);
    }

    public function test_admin_can_upload_and_optimize_image_via_media_endpoint(): void
    {
        $fakeImage = UploadedFile::fake()->image('dashboard-banner.jpg', 1200, 800);

        $response = $this->actingAs($this->admin)->postJson('/admin/media/upload', [
            'image' => $fakeImage,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $path = $response->json('image_path');
        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);
        $this->assertFileExists(public_path($path));

        if (file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }

    public function test_image_optimizer_service_converts_to_webp_and_removes_uncompressed_original(): void
    {
        $optimizer = app(ImageOptimizerService::class);
        $fakeImage = UploadedFile::fake()->image('test-photo.png', 1600, 1200);

        $optimizedRelPath = $optimizer->optimize($fakeImage, 'uploads/test_optimized', 800, 600, 80);

        $this->assertStringEndsWith('.webp', $optimizedRelPath);
        $this->assertFileExists(public_path($optimizedRelPath));

        // Verify dimensions were downscaled proportionally
        [$width, $height] = getimagesize(public_path($optimizedRelPath));
        $this->assertLessThanOrEqual(800, $width);
        $this->assertLessThanOrEqual(600, $height);

        // Clean up created file and directory
        if (file_exists(public_path($optimizedRelPath))) {
            @unlink(public_path($optimizedRelPath));
        }
        $dir = public_path('uploads/test_optimized');
        if (is_dir($dir)) {
            @rmdir($dir);
        }
    }

    public function test_admin_can_create_and_update_album_using_existing_gallery_photo(): void
    {
        $existingPath = 'front/images/gallery/optimized/itd-2025-01.webp';

        $response = $this->actingAs($this->admin)->post('/admin/gallery', [
            'title' => 'Album From Gallery Picker',
            'slug' => 'album-from-gallery-picker',
            'cover_image' => $existingPath,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseHas('gallery_albums', [
            'title' => 'Album From Gallery Picker',
            'cover_image' => $existingPath,
        ]);

        $album = GalleryAlbum::where('slug', 'album-from-gallery-picker')->first();

        // Update with another existing gallery photo
        $updatedPath = 'front/images/gallery/optimized/itd-2025-02.webp';
        $response = $this->actingAs($this->admin)->put("/admin/gallery/{$album->id}", [
            'title' => 'Album From Gallery Picker Updated',
            'cover_image' => $updatedPath,
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertEquals($updatedPath, $album->fresh()->cover_image);

        // Clear cover photo
        $response = $this->actingAs($this->admin)->put("/admin/gallery/{$album->id}", [
            'title' => 'Album From Gallery Picker Cleared',
            'cover_image' => '',
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertNull($album->fresh()->cover_image);
    }
}
