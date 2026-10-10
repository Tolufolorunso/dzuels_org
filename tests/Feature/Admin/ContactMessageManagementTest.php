<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageManagementTest extends TestCase
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

    public function test_guest_cannot_access_contact_message_management(): void
    {
        $message = ContactMessage::create([
            'first_name' => 'Anonymous',
            'last_name' => 'Sender',
            'email' => 'anon@example.com',
            'subject' => 'Secret inquiry',
            'message' => 'Please keep this confidential.',
            'status' => 'unread',
        ]);

        $this->get('/admin/messages')->assertRedirect('/admin/login');
        $this->patch("/admin/messages/{$message->id}/status", ['status' => 'read'])->assertRedirect('/admin/login');
        $this->delete("/admin/messages/{$message->id}")->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_contact_messages_list(): void
    {
        ContactMessage::create([
            'first_name' => 'Kehinde',
            'last_name' => 'Adeyemi',
            'email' => 'kehinde@example.com',
            'phone' => '+234 802 333 4444',
            'subject' => 'Partnership inquiry for Ekiti North',
            'message' => 'We are coordinating educational materials for public schools in Ekiti North.',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/messages');

        $response->assertOk();
        $response->assertSeeText('Kehinde Adeyemi');
        $response->assertSeeText('kehinde@example.com');
        $response->assertSeeText('Partnership inquiry for Ekiti North');
    }

    public function test_admin_can_filter_messages_by_status_and_search(): void
    {
        ContactMessage::create([
            'first_name' => 'Unread',
            'last_name' => 'User',
            'email' => 'unread@example.com',
            'subject' => 'Urgent Lab Question',
            'message' => 'Need details on lab dates.',
            'status' => 'unread',
        ]);

        ContactMessage::create([
            'first_name' => 'Read',
            'last_name' => 'User',
            'email' => 'read@example.com',
            'subject' => 'Resolved inquiry',
            'message' => 'Thank you for answering.',
            'status' => 'read',
        ]);

        // Filter status=unread
        $responseUnread = $this->actingAs($this->admin)->get('/admin/messages?status=unread');
        $responseUnread->assertOk();
        $responseUnread->assertSeeText('Unread User');
        $responseUnread->assertDontSeeText('Read User');

        // Search query
        $responseSearch = $this->actingAs($this->admin)->get('/admin/messages?q=Urgent');
        $responseSearch->assertOk();
        $responseSearch->assertSeeText('Urgent Lab Question');
        $responseSearch->assertDontSeeText('Resolved inquiry');
    }

    public function test_admin_can_update_message_status(): void
    {
        $message = ContactMessage::create([
            'first_name' => 'Bisi',
            'last_name' => 'Ogunjobi',
            'email' => 'bisi@example.com',
            'subject' => 'Volunteer coordination',
            'message' => 'When does the next training cohort start?',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch("/admin/messages/{$message->id}/status", [
                'status' => 'replied',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('replied', $message->fresh()->status);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $message = ContactMessage::create([
            'first_name' => 'Spam',
            'last_name' => 'Promoter',
            'email' => 'spam@promotions.org',
            'subject' => 'Cheap SEO Packages',
            'message' => 'Rank #1 on Google guaranteed.',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/admin/messages/{$message->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }
}
