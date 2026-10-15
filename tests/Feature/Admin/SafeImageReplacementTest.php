<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryAlbum;
use App\Models\Programme;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\ImageOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class SafeImageReplacementTest extends TestCase
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

    public function test_programme_update_preserves_old_image_when_optimization_fails(): void
    {
        $oldImagePath = 'uploads/programmes/test_old_prog.webp';
        $fullOldPath = public_path($oldImagePath);
        @mkdir(dirname($fullOldPath), 0755, true);
        file_put_contents($fullOldPath, 'dummy old content');

        $programme = Programme::create([
            'title' => 'Youth Tech Empowerment',
            'slug' => 'youth-tech-empowerment',
            'summary' => 'Summary text',
            'body' => 'Body text',
            'cover_image' => $oldImagePath,
            'is_active' => true,
        ]);

        $mockOptimizer = Mockery::mock(ImageOptimizerService::class);
        $mockOptimizer->shouldReceive('optimize')
            ->once()
            ->andThrow(new \RuntimeException('Simulated GD out-of-memory failure'));
        $this->app->instance(ImageOptimizerService::class, $mockOptimizer);

        $file = UploadedFile::fake()->image('new_cover.jpg', 800, 600);

        try {
            $this->actingAs($this->admin)->put("/admin/programmes/{$programme->id}", [
                'title' => 'Youth Tech Empowerment Updated',
                'summary' => 'Summary text',
                'body' => 'Body text',
                'cover_image' => $file,
            ]);
        } catch (\RuntimeException) {
            // Handled or uncaught, but old file must remain safe
        }

        $this->assertFileExists($fullOldPath, 'Existing cover image file was deleted prematurely!');
        $this->assertEquals($oldImagePath, $programme->fresh()->cover_image);

        if (file_exists($fullOldPath)) {
            @unlink($fullOldPath);
        }
    }

    public function test_team_member_update_preserves_old_photo_when_optimization_fails(): void
    {
        $oldPhotoPath = 'uploads/team/test_old_member.webp';
        $fullOldPath = public_path($oldPhotoPath);
        @mkdir(dirname($fullOldPath), 0755, true);
        file_put_contents($fullOldPath, 'dummy old photo');

        $team = TeamMember::create([
            'name' => 'Dr. Jane Doe',
            'role' => 'Executive Director',
            'group' => 'trustee',
            'photo' => $oldPhotoPath,
            'is_active' => true,
        ]);

        $mockOptimizer = Mockery::mock(ImageOptimizerService::class);
        $mockOptimizer->shouldReceive('optimize')
            ->once()
            ->andThrow(new \RuntimeException('Simulated optimization exception'));
        $this->app->instance(ImageOptimizerService::class, $mockOptimizer);

        $file = UploadedFile::fake()->image('new_headshot.jpg', 400, 400);

        try {
            $this->actingAs($this->admin)->put("/admin/team/{$team->id}", [
                'name' => 'Dr. Jane Doe Updated',
                'role' => 'Executive Director',
                'group' => 'trustee',
                'photo' => $file,
            ]);
        } catch (\RuntimeException) {
            // Handled or uncaught, but old file must remain safe
        }

        $this->assertFileExists($fullOldPath, 'Existing team photo file was deleted prematurely!');
        $this->assertEquals($oldPhotoPath, $team->fresh()->photo);

        if (file_exists($fullOldPath)) {
            @unlink($fullOldPath);
        }
    }

    public function test_gallery_album_update_preserves_old_cover_when_optimization_fails(): void
    {
        $oldCoverPath = 'uploads/gallery/covers/test_old_album.webp';
        $fullOldPath = public_path($oldCoverPath);
        @mkdir(dirname($fullOldPath), 0755, true);
        file_put_contents($fullOldPath, 'dummy old album cover');

        $album = GalleryAlbum::create([
            'title' => 'Community Outreach 2026',
            'slug' => 'community-outreach-2026',
            'cover_image' => $oldCoverPath,
            'is_active' => true,
        ]);

        $mockOptimizer = Mockery::mock(ImageOptimizerService::class);
        $mockOptimizer->shouldReceive('optimize')
            ->once()
            ->andThrow(new \RuntimeException('Simulated album cover optimization error'));
        $this->app->instance(ImageOptimizerService::class, $mockOptimizer);

        $file = UploadedFile::fake()->image('new_album_cover.jpg', 600, 400);

        try {
            $this->actingAs($this->admin)->put("/admin/gallery/{$album->id}", [
                'title' => 'Community Outreach 2026 Updated',
                'cover_image' => $file,
            ]);
        } catch (\RuntimeException) {
            // Handled or uncaught, but old file must remain safe
        }

        $this->assertFileExists($fullOldPath, 'Existing album cover image was deleted prematurely!');
        $this->assertEquals($oldCoverPath, $album->fresh()->cover_image);

        if (file_exists($fullOldPath)) {
            @unlink($fullOldPath);
        }
    }

    public function test_programme_update_deletes_old_image_after_successful_optimization(): void
    {
        $oldImagePath = 'uploads/programmes/test_old_prog_success.webp';
        $fullOldPath = public_path($oldImagePath);
        @mkdir(dirname($fullOldPath), 0755, true);
        file_put_contents($fullOldPath, 'dummy old content');

        $newImagePath = 'uploads/programmes/test_new_prog_success.webp';
        $fullNewPath = public_path($newImagePath);
        file_put_contents($fullNewPath, 'dummy new content');

        $programme = Programme::create([
            'title' => 'Youth Tech Empowerment',
            'slug' => 'youth-tech-empowerment',
            'summary' => 'Summary text',
            'body' => 'Body text',
            'cover_image' => $oldImagePath,
            'is_active' => true,
        ]);

        $mockOptimizer = Mockery::mock(ImageOptimizerService::class);
        $mockOptimizer->shouldReceive('optimize')
            ->once()
            ->andReturn($newImagePath);
        $this->app->instance(ImageOptimizerService::class, $mockOptimizer);

        $file = UploadedFile::fake()->image('new_cover.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->put("/admin/programmes/{$programme->id}", [
            'title' => 'Youth Tech Empowerment Updated',
            'summary' => 'Summary text',
            'body' => 'Body text',
            'cover_image' => $file,
        ]);

        $response->assertRedirect();
        $this->assertFileDoesNotExist($fullOldPath, 'Old image file was not deleted after successful update');
        $this->assertEquals($newImagePath, $programme->fresh()->cover_image);

        if (file_exists($fullNewPath)) {
            @unlink($fullNewPath);
        }
    }

    public function test_team_member_update_deletes_old_photo_after_successful_optimization(): void
    {
        $oldPhotoPath = 'uploads/team/test_old_member_success.webp';
        $fullOldPath = public_path($oldPhotoPath);
        @mkdir(dirname($fullOldPath), 0755, true);
        file_put_contents($fullOldPath, 'dummy old photo');

        $newPhotoPath = 'uploads/team/test_new_member_success.webp';
        $fullNewPath = public_path($newPhotoPath);
        file_put_contents($fullNewPath, 'dummy new photo');

        $team = TeamMember::create([
            'name' => 'Dr. Jane Doe',
            'role' => 'Executive Director',
            'group' => 'trustee',
            'photo' => $oldPhotoPath,
            'is_active' => true,
        ]);

        $mockOptimizer = Mockery::mock(ImageOptimizerService::class);
        $mockOptimizer->shouldReceive('optimize')
            ->once()
            ->andReturn($newPhotoPath);
        $this->app->instance(ImageOptimizerService::class, $mockOptimizer);

        $file = UploadedFile::fake()->image('new_headshot.jpg', 400, 400);

        $response = $this->actingAs($this->admin)->put("/admin/team/{$team->id}", [
            'name' => 'Dr. Jane Doe Updated',
            'role' => 'Executive Director',
            'group' => 'trustee',
            'photo' => $file,
        ]);

        $response->assertRedirect();
        $this->assertFileDoesNotExist($fullOldPath, 'Old photo file was not deleted after successful update');
        $this->assertEquals($newPhotoPath, $team->fresh()->photo);

        if (file_exists($fullNewPath)) {
            @unlink($fullNewPath);
        }
    }

    public function test_gallery_album_update_deletes_old_cover_after_successful_optimization(): void
    {
        $oldCoverPath = 'uploads/gallery/covers/test_old_album_success.webp';
        $fullOldPath = public_path($oldCoverPath);
        @mkdir(dirname($fullOldPath), 0755, true);
        file_put_contents($fullOldPath, 'dummy old album cover');

        $newCoverPath = 'uploads/gallery/covers/test_new_album_success.webp';
        $fullNewPath = public_path($newCoverPath);
        file_put_contents($fullNewPath, 'dummy new album cover');

        $album = GalleryAlbum::create([
            'title' => 'Community Outreach 2026',
            'slug' => 'community-outreach-2026',
            'cover_image' => $oldCoverPath,
            'is_active' => true,
        ]);

        $mockOptimizer = Mockery::mock(ImageOptimizerService::class);
        $mockOptimizer->shouldReceive('optimize')
            ->once()
            ->andReturn($newCoverPath);
        $this->app->instance(ImageOptimizerService::class, $mockOptimizer);

        $file = UploadedFile::fake()->image('new_album_cover.jpg', 600, 400);

        $response = $this->actingAs($this->admin)->put("/admin/gallery/{$album->id}", [
            'title' => 'Community Outreach 2026 Updated',
            'cover_image' => $file,
        ]);

        $response->assertRedirect();
        $this->assertFileDoesNotExist($fullOldPath, 'Old cover file was not deleted after successful update');
        $this->assertEquals($newCoverPath, $album->fresh()->cover_image);

        if (file_exists($fullNewPath)) {
            @unlink($fullNewPath);
        }
    }
}
