<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSeeText('Staff Administration');
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/settings');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_authenticate_with_valid_credentials(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'password' => Hash::make('secret-password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@dzuelsfoundation.org',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect('/admin/settings');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_authenticate_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'password' => Hash::make('secret-password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@dzuelsfoundation.org',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_access_admin_panel(): void
    {
        User::factory()->create([
            'email' => 'inactive@dzuelsfoundation.org',
            'password' => Hash::make('secret-password'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'inactive@dzuelsfoundation.org',
            'password' => 'secret-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_admin_can_logout(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
