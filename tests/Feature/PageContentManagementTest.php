<?php

namespace Tests\Feature;

use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PageContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PageSectionSeeder::class);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_from_admin_pages(): void
    {
        $response = $this->get('/admin/pages');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_pages_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/pages');

        $response->assertStatus(200);
        $response->assertSee('Home Page');
        $response->assertSee('About Us');
        $response->assertSee('Programmes Index');
        $response->assertSee('Contact Us');
        $response->assertSee('Donate & Support');
        $response->assertSee('Get Involved');
    }

    public function test_admin_can_view_page_editor(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/pages/home');

        $response->assertStatus(200);
        $response->assertSee('Editing Home Page');
        $response->assertSee('Section: Hero');
        $response->assertSee('Section: Who We Are');
    }

    public function test_admin_can_update_page_section(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/pages/home/sections/hero', [
            'headline' => 'New Hero Headline Text',
            'subheadline' => 'Custom Subheadline',
            'body_text' => 'New body copy description for testing.',
            'button_text' => 'Custom Button',
            'button_url' => '/donate',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('page_sections', [
            'page_key' => 'home',
            'section_key' => 'hero',
            'headline' => 'New Hero Headline Text',
            'subheadline' => 'Custom Subheadline',
            'body_text' => 'New body copy description for testing.',
            'button_text' => 'Custom Button',
            'button_url' => '/donate',
        ]);
    }

    public function test_admin_can_upload_section_image(): void
    {
        $fakeImage = UploadedFile::fake()->image('test-banner.webp', 800, 600);

        $response = $this->actingAs($this->admin)->put('/admin/pages/home/sections/hero', [
            'headline' => 'Updated Banner',
            'image' => $fakeImage,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $section = PageSection::where('page_key', 'home')->where('section_key', 'hero')->first();
        $this->assertNotNull($section);
        $this->assertNotNull($section->image_path);
        $this->assertStringStartsWith('uploads/cms/', $section->image_path);
        $this->assertStringEndsWith('.webp', $section->image_path);

        $fullPath = public_path($section->image_path);
        $this->assertTrue(File::exists($fullPath));

        // Clean up created file
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    public function test_invalid_page_key_returns_404(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/pages/non_existent_page');
        $response->assertStatus(404);
    }
}
