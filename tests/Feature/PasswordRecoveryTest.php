<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'staff@dzuelsfoundation.org',
            'password' => Hash::make('oldSecretPassword123'),
            'role' => 'editor',
            'is_active' => true,
        ]);
    }

    public function test_login_page_displays_forgot_password_link(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Forgot password?');
        $response->assertSee(route('admin.password.request'));
    }

    public function test_guest_can_view_forgot_password_form(): void
    {
        $response = $this->get('/admin/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Forgot Password');
        $response->assertSee('Email Password Reset Link');
    }

    public function test_submitting_registered_email_sends_reset_notification(): void
    {
        Notification::fake();

        $response = $this->post('/admin/forgot-password', [
            'email' => 'staff@dzuelsfoundation.org',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        Notification::assertSentTo($this->user, ResetPassword::class);
    }

    public function test_submitting_unknown_email_returns_error(): void
    {
        $response = $this->post('/admin/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_guest_can_view_reset_password_form_with_token(): void
    {
        $token = Password::createToken($this->user);

        $response = $this->get("/admin/reset-password/{$token}?email=staff@dzuelsfoundation.org");

        $response->assertStatus(200);
        $response->assertSee('Set New Password');
        $response->assertSee($token);
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/admin/reset-password', [
            'token' => $token,
            'email' => 'staff@dzuelsfoundation.org',
            'password' => 'newBrandSecret999',
            'password_confirmation' => 'newBrandSecret999',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHas('status');

        $this->user->refresh();
        $this->assertTrue(Hash::check('newBrandSecret999', $this->user->password));
    }

    public function test_reset_fails_with_invalid_token(): void
    {
        $response = $this->post('/admin/reset-password', [
            'token' => 'invalid-token-12345',
            'email' => 'staff@dzuelsfoundation.org',
            'password' => 'newBrandSecret999',
            'password_confirmation' => 'newBrandSecret999',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(Hash::check('newBrandSecret999', $this->user->fresh()->password));
    }

    public function test_authenticated_user_can_view_profile_password_settings(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee('Change Your Password');
        $response->assertSee('staff@dzuelsfoundation.org');
    }

    public function test_authenticated_user_can_update_password_with_correct_current_password(): void
    {
        $response = $this->actingAs($this->user)->put('/admin/profile/password', [
            'current_password' => 'oldSecretPassword123',
            'password' => 'brandNewSecurePass456',
            'password_confirmation' => 'brandNewSecurePass456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->user->refresh();
        $this->assertTrue(Hash::check('brandNewSecurePass456', $this->user->password));
    }

    public function test_authenticated_user_cannot_update_password_with_wrong_current_password(): void
    {
        $response = $this->actingAs($this->user)->put('/admin/profile/password', [
            'current_password' => 'wrongPassword',
            'password' => 'brandNewSecurePass456',
            'password_confirmation' => 'brandNewSecurePass456',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertFalse(Hash::check('brandNewSecurePass456', $this->user->fresh()->password));
    }
}
