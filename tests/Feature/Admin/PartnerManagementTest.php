<?php

namespace Tests\Feature\Admin;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PartnerManagementTest extends TestCase
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

    public function test_guest_cannot_access_partner_management(): void
    {
        $response = $this->get('/admin/partners');
        $response->assertRedirect('/admin/login');

        $responseCreate = $this->get('/admin/partners/create');
        $responseCreate->assertRedirect('/admin/login');

        $responsePost = $this->post('/admin/partners', []);
        $responsePost->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_partners_list(): void
    {
        Partner::create([
            'name' => 'ASCEND Education Foundation',
            'logo' => 'front/images/ascend.jpg',
            'website_url' => 'https://ascendedufoundation.org/',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/partners');

        $response->assertOk();
        $response->assertSeeText('ASCEND Education Foundation');
    }

    public function test_admin_can_create_partner_with_logo(): void
    {
        $logo = UploadedFile::fake()->image('partner_logo.png', 150, 50);

        $response = $this->actingAs($this->admin)->post('/admin/partners', [
            'name' => 'SeedDev Initiative',
            'website_url' => 'https://seeddev.org',
            'sort_order' => 2,
            'is_active' => '1',
            'logo' => $logo,
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('partners', [
            'name' => 'SeedDev Initiative',
            'website_url' => 'https://seeddev.org',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $created = Partner::where('name', 'SeedDev Initiative')->first();
        $this->assertNotNull($created->logo);
        $this->assertTrue(File::exists(public_path($created->logo)));

        // Clean up test file
        if (File::exists(public_path($created->logo))) {
            File::delete(public_path($created->logo));
        }
    }

    public function test_partner_validation_fails_for_invalid_data(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/partners', [
            'name' => '',
            'website_url' => 'not-a-valid-url',
        ]);

        $response->assertSessionHasErrors(['name', 'logo', 'website_url']);
    }

    public function test_admin_can_update_partner(): void
    {
        $partner = Partner::create([
            'name' => 'Old Partner',
            'logo' => 'front/images/SAA.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/partners/{$partner->id}", [
            'name' => 'Updated Partner Name',
            'website_url' => 'https://updated-partner.org',
            'sort_order' => 3,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => 'Updated Partner Name',
            'website_url' => 'https://updated-partner.org',
            'sort_order' => 3,
        ]);
    }

    public function test_admin_can_toggle_partner_status(): void
    {
        $partner = Partner::create([
            'name' => 'Toggle Partner',
            'logo' => 'front/images/SAA.jpg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/partners/{$partner->id}/toggle");

        $response->assertRedirect();
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'is_active' => false,
        ]);

        $this->actingAs($this->admin)->patch("/admin/partners/{$partner->id}/toggle");
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_partner(): void
    {
        $partner = Partner::create([
            'name' => 'Delete Partner',
            'logo' => 'front/images/SAA.jpg',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/partners/{$partner->id}");

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseMissing('partners', [
            'id' => $partner->id,
        ]);
    }

    public function test_partner_creation_rejects_non_image_logo_file(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post('/admin/partners', [
            'name' => 'Partner Org',
            'logo' => $file,
        ]);

        $response->assertSessionHasErrors(['logo']);
    }

    public function test_partner_update_rejects_non_image_logo_file(): void
    {
        $partner = Partner::create([
            'name' => 'Partner Org',
            'logo' => 'front/images/SAA.jpg',
        ]);

        $file = UploadedFile::fake()->create('archive.zip', 500, 'application/zip');

        $response = $this->actingAs($this->admin)->put("/admin/partners/{$partner->id}", [
            'name' => 'Updated Org',
            'logo' => $file,
        ]);

        $response->assertSessionHasErrors(['logo']);
    }
}
