<?php

namespace Tests\Feature\Admin;

use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProgrammeManagementTest extends TestCase
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
        $response = $this->get('/admin/programmes');
        $response->assertRedirect('/admin/login');

        $response = $this->get('/admin/programmes/create');
        $response->assertRedirect('/admin/login');

        $response = $this->post('/admin/programmes', []);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_programmes_index(): void
    {
        Programme::create([
            'title' => 'Digital Literacy Initiative',
            'slug' => 'digital-literacy',
            'summary' => 'Empowering youth with digital skills.',
            'body' => 'Full narrative of the digital literacy programme.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/programmes');

        $response->assertOk();
        $response->assertSeeText('Foundation Programmes');
        $response->assertSeeText('Digital Literacy Initiative');
        $response->assertSeeText('/programmes/digital-literacy');
    }

    public function test_admin_can_create_programme_with_uploaded_image(): void
    {
        $image = UploadedFile::fake()->image('initiative.jpg', 600, 400);

        $response = $this->actingAs($this->admin)->post('/admin/programmes', [
            'title' => 'Tech Scholars Hub',
            'slug' => 'tech-scholars-hub',
            'summary' => 'Comprehensive scholarships for secondary students.',
            'body' => 'Full multi-paragraph text explaining scholarship terms and criteria.',
            'partner_name' => 'SeedDev',
            'cover_image' => $image,
            'is_featured' => '1',
            'is_active' => '1',
            'sort_order' => '5',
            'seo_title' => 'Tech Scholars Hub | Dzuels',
            'seo_description' => 'Scholarship initiative in Ekiti.',
        ]);

        $response->assertRedirect(route('admin.programmes.index'));
        $response->assertSessionHas('success', 'Programme created successfully.');

        $this->assertDatabaseHas('programmes', [
            'title' => 'Tech Scholars Hub',
            'slug' => 'tech-scholars-hub',
            'partner_name' => 'SeedDev',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 5,
        ]);

        $programme = Programme::where('slug', 'tech-scholars-hub')->first();
        $this->assertNotNull($programme->cover_image);
        $this->assertStringStartsWith('uploads/programmes/', $programme->cover_image);

        // Clean up created fake file
        if (file_exists(public_path($programme->cover_image))) {
            @unlink(public_path($programme->cover_image));
        }
    }

    public function test_admin_can_update_programme(): void
    {
        $programme = Programme::create([
            'title' => 'Initial Title',
            'slug' => 'initial-title',
            'summary' => 'Initial summary',
            'body' => 'Initial body narrative',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.programmes.update', $programme), [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'summary' => 'Updated summary text',
            'body' => 'Updated body narrative text',
            'sort_order' => 2,
            'is_featured' => '1',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.programmes.index'));
        $response->assertSessionHas('success', 'Programme updated successfully.');

        $this->assertDatabaseHas('programmes', [
            'id' => $programme->id,
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_toggle_programme_active_status(): void
    {
        $programme = Programme::create([
            'title' => 'Toggle Programme',
            'slug' => 'toggle-programme',
            'summary' => 'Summary text',
            'body' => 'Body text',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.programmes.toggle', $programme));

        $response->assertRedirect(route('admin.programmes.index'));
        $this->assertFalse($programme->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($this->admin)->patch(route('admin.programmes.toggle', $programme));
        $this->assertTrue($programme->fresh()->is_active);
    }

    public function test_admin_can_delete_programme(): void
    {
        $programme = Programme::create([
            'title' => 'Programme To Delete',
            'slug' => 'programme-to-delete',
            'summary' => 'Summary',
            'body' => 'Body',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.programmes.destroy', $programme));

        $response->assertRedirect(route('admin.programmes.index'));
        $this->assertDatabaseMissing('programmes', [
            'id' => $programme->id,
        ]);
    }

    public function test_programme_validation_requires_title_summary_and_body(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/programmes', [
            'title' => '',
            'summary' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'summary', 'body']);
    }
}
