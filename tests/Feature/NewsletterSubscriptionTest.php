<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_subscribe_via_footer_form(): void
    {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'visitor@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('newsletter_success', 'Thank you for subscribing to Dzuels updates!');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'visitor@example.com',
            'unsubscribed_at' => null,
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'visitor@example.com')->first();
        $this->assertNotNull($subscriber);
        $this->assertNotNull($subscriber->subscribed_at);
        $this->assertNotNull($subscriber->unsubscribe_token);
        $this->assertTrue($subscriber->is_active);
    }

    public function test_json_subscription_receives_json_response(): void
    {
        $response = $this->postJson('/newsletter/subscribe', [
            'email' => 'ajax_donor@example.org',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Thank you for subscribing to Dzuels updates!',
        ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'ajax_donor@example.org',
        ]);
    }

    public function test_duplicate_active_subscription_does_not_create_duplicate_record(): void
    {
        NewsletterSubscriber::create([
            'email' => 'already_active@example.com',
            'subscribed_at' => now()->subMonth(),
            'unsubscribe_token' => 'existing-token-12345',
        ]);

        $response = $this->postJson('/newsletter/subscribe', [
            'email' => 'already_active@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'You are already subscribed to Dzuels updates!',
        ]);

        $this->assertEquals(1, NewsletterSubscriber::where('email', 'already_active@example.com')->count());
    }

    public function test_resubscribing_reactivates_unsubscribed_email(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'returning_friend@example.com',
            'subscribed_at' => now()->subMonths(6),
            'unsubscribed_at' => now()->subMonths(2),
            'unsubscribe_token' => 'old-token',
        ]);

        $this->assertFalse($subscriber->is_active);

        $response = $this->postJson('/newsletter/subscribe', [
            'email' => 'returning_friend@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Welcome back! Your subscription to Dzuels updates has been reactivated.',
        ]);

        $subscriber->refresh();
        $this->assertTrue($subscriber->is_active);
        $this->assertNull($subscriber->unsubscribed_at);
        $this->assertNotEquals('old-token', $subscriber->unsubscribe_token);
    }

    public function test_honeypot_submission_is_treated_as_success_without_saving(): void
    {
        $response = $this->postJson('/newsletter/subscribe', [
            'email' => 'spambot@example.com',
            '_dzuels_hp' => 'I am a bot',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Thank you for subscribing to Dzuels updates!',
        ]);

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'email' => 'spambot@example.com',
        ]);
    }

    public function test_invalid_email_fails_validation(): void
    {
        $response = $this->postJson('/newsletter/subscribe', [
            'email' => 'not-an-email',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_subscriber_can_view_unsubscribe_page_with_valid_token(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'member@example.com',
            'subscribed_at' => now(),
            'unsubscribe_token' => 'test-valid-unsubscribe-token',
        ]);

        $response = $this->get('/newsletter/unsubscribe/test-valid-unsubscribe-token');

        $response->assertOk();
        $response->assertSeeText('Unsubscribe from Updates');
        $response->assertSeeText('member@example.com');
        $response->assertSeeText('Confirm Unsubscribe');
    }

    public function test_subscriber_can_confirm_unsubscription(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'optout@example.com',
            'subscribed_at' => now(),
            'unsubscribe_token' => 'test-token-to-cancel',
        ]);

        $this->assertTrue($subscriber->is_active);

        $response = $this->post('/newsletter/unsubscribe/test-token-to-cancel');

        $response->assertOk();
        $response->assertSeeText('You Have Been Unsubscribed');
        $response->assertSeeText('optout@example.com');

        $subscriber->refresh();
        $this->assertFalse($subscriber->is_active);
        $this->assertNotNull($subscriber->unsubscribed_at);
    }

    public function test_invalid_unsubscribe_token_returns_404(): void
    {
        $response = $this->get('/newsletter/unsubscribe/completely-invalid-token');

        $response->assertNotFound();
    }
}
