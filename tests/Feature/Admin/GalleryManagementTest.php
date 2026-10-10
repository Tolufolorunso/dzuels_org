<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryAlbum;
use App\Models\User;
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
}
