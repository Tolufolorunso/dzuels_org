<?php

namespace Tests\Feature\Admin;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterManagementTest extends TestCase
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
        $response = $this->get('/admin/subscribers');
        $response->assertRedirect('/admin/login');

        $response = $this->get('/admin/subscribers/export');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_subscribers_index(): void
    {
        NewsletterSubscriber::create([
            'email' => 'active_donor@example.com',
            'subscribed_at' => now(),
            'unsubscribe_token' => 'active-token-1',
        ]);

        NewsletterSubscriber::create([
            'email' => 'left_member@example.com',
            'subscribed_at' => now()->subYear(),
            'unsubscribed_at' => now()->subMonth(),
            'unsubscribe_token' => 'unsubscribed-token-2',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/subscribers');

        $response->assertOk();
        $response->assertSeeText('Newsletter Subscribers');
        $response->assertSeeText('active_donor@example.com');
        $response->assertSeeText('left_member@example.com');
        $response->assertSeeText('Active');
        $response->assertSeeText('Unsubscribed');
    }

    public function test_admin_can_filter_and_search_subscribers(): void
    {
        NewsletterSubscriber::create([
            'email' => 'active_volunteer@example.com',
            'subscribed_at' => now(),
            'unsubscribe_token' => 'token-a',
        ]);

        NewsletterSubscriber::create([
            'email' => 'cancelled_person@example.com',
            'subscribed_at' => now()->subMonths(3),
            'unsubscribed_at' => now()->subDay(),
            'unsubscribe_token' => 'token-b',
        ]);

        // Search test
        $response = $this->actingAs($this->admin)->get('/admin/subscribers?search=volunteer');
        $response->assertOk();
        $response->assertSeeText('active_volunteer@example.com');
        $response->assertDontSeeText('cancelled_person@example.com');

        // Active filter test
        $response = $this->actingAs($this->admin)->get('/admin/subscribers?filter=active');
        $response->assertOk();
        $response->assertSeeText('active_volunteer@example.com');
        $response->assertDontSeeText('cancelled_person@example.com');

        // Unsubscribed filter test
        $response = $this->actingAs($this->admin)->get('/admin/subscribers?filter=unsubscribed');
        $response->assertOk();
        $response->assertDontSeeText('active_volunteer@example.com');
        $response->assertSeeText('cancelled_person@example.com');
    }

    public function test_admin_can_export_subscribers_as_csv(): void
    {
        NewsletterSubscriber::create([
            'email' => 'export1@example.com',
            'subscribed_at' => now(),
            'unsubscribe_token' => 'token-exp-1',
        ]);

        NewsletterSubscriber::create([
            'email' => 'export2@example.com',
            'subscribed_at' => now()->subMonths(2),
            'unsubscribed_at' => now()->subMonth(),
            'unsubscribe_token' => 'token-exp-2',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/subscribers/export');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('dzuels_newsletter_subscribers_', $response->headers->get('Content-Disposition'));

        // Stream output check
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('ID,Email,Status,"Subscribed At","Unsubscribed At"', $content);
        $this->assertStringContainsString('export1@example.com,Active', $content);
        $this->assertStringContainsString('export2@example.com,Unsubscribed', $content);
    }

    public function test_admin_can_delete_subscriber(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'remove_me@example.com',
            'subscribed_at' => now(),
            'unsubscribe_token' => 'del-token',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.subscribers.destroy', $subscriber));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Subscriber removed successfully.');

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'id' => $subscriber->id,
        ]);
    }
}
