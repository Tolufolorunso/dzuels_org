<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected User $editor;

    protected User $support;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'name' => 'Lead Administrator',
            'email' => 'superadmin@dzuelsfoundation.org',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->editor = User::factory()->create([
            'name' => 'Content Specialist',
            'email' => 'editor@dzuelsfoundation.org',
            'role' => 'editor',
            'is_active' => true,
        ]);

        $this->support = User::factory()->create([
            'name' => 'Support Desk',
            'email' => 'support@dzuelsfoundation.org',
            'role' => 'support',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_from_user_management(): void
    {
        $response = $this->get('/admin/users');
        $response->assertRedirect('/admin/login');
    }

    public function test_editor_is_forbidden_from_user_management_and_settings(): void
    {
        $response = $this->actingAs($this->editor)->get('/admin/users');
        $response->assertStatus(403);

        $responseSettings = $this->actingAs($this->editor)->get('/admin/settings');
        $responseSettings->assertStatus(403);
    }

    public function test_editor_can_access_cms_pages(): void
    {
        $response = $this->actingAs($this->editor)->get('/admin/pages');
        $response->assertStatus(200);
    }

    public function test_super_admin_can_view_users_list(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Lead Administrator');
        $response->assertSee('Content Specialist');
        $response->assertSee('Support Desk');
    }

    public function test_super_admin_can_create_staff_account(): void
    {
        $response = $this->actingAs($this->superAdmin)->post('/admin/users', [
            'name' => 'New Staff Member',
            'email' => 'newstaff@dzuelsfoundation.org',
            'role' => 'editor',
            'password' => 'secretPass123',
            'password_confirmation' => 'secretPass123',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New Staff Member',
            'email' => 'newstaff@dzuelsfoundation.org',
            'role' => 'editor',
            'is_active' => 1,
        ]);

        $newUser = User::where('email', 'newstaff@dzuelsfoundation.org')->first();
        $this->assertTrue(Hash::check('secretPass123', $newUser->password));
    }

    public function test_super_admin_can_update_staff_account(): void
    {
        $response = $this->actingAs($this->superAdmin)->put("/admin/users/{$this->editor->id}", [
            'name' => 'Senior Content Specialist',
            'email' => 'editor@dzuelsfoundation.org',
            'role' => 'editor',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->editor->id,
            'name' => 'Senior Content Specialist',
        ]);
    }

    public function test_super_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete("/admin/users/{$this->superAdmin->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->superAdmin->id,
        ]);
    }

    public function test_super_admin_cannot_demote_or_deactivate_last_super_admin(): void
    {
        // Only one superAdmin exists in this test
        $response = $this->actingAs($this->superAdmin)->put("/admin/users/{$this->superAdmin->id}", [
            'name' => 'Lead Administrator',
            'email' => 'superadmin@dzuelsfoundation.org',
            'role' => 'editor', // Attempting demotion
            'is_active' => '1',
        ]);

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->superAdmin->id,
            'role' => 'super_admin',
        ]);
    }

    public function test_super_admin_can_toggle_user_status(): void
    {
        $this->assertTrue($this->editor->is_active);

        $response = $this->actingAs($this->superAdmin)->patch("/admin/users/{$this->editor->id}/toggle");

        $response->assertRedirect();
        $this->assertFalse($this->editor->fresh()->is_active);
    }

    public function test_deactivated_user_cannot_login(): void
    {
        $this->editor->update(['is_active' => false]);

        $response = $this->post('/admin/login', [
            'email' => 'editor@dzuelsfoundation.org',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
