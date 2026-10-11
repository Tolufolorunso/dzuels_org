<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRedesignTest extends TestCase
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
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_redesigned_dashboard(): void
    {
        $event = Event::create([
            'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Empowering 300 children with AI tools.',
            'description' => 'Full event description of Ijero Tech-Day 2026.',
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'theme' => 'Learning Today, Creating Tomorrow',
            'event_date' => now()->addDays(10),
            'sort_order' => 1,
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        $event->registrations()->create([
            'full_name' => 'Tolu Folorunso',
            'email' => 'tolu@example.com',
            'phone' => '08012345678',
            'attendee_type' => 'student',
        ]);

        Programme::create([
            'title' => 'Grassroots AI Lab',
            'slug' => 'grassroots-ai-lab',
            'summary' => 'Empowering youth with AI tools.',
            'body' => 'Full details on the grassroots lab.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        ContactMessage::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'subject' => 'Partnership Inquiry',
            'message' => 'We would love to partner.',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('Dzuels Foundation Operations Center');
        $response->assertSee('Total Event Registrations');
        $response->assertSee('Active Initiatives');
        $response->assertSee('Contact Messages');
        $response->assertSee('Ijero Tech-Day 2026 (ITD 2026)');
        $response->assertSee('Tolu Folorunso');
        $response->assertSee('Dashboard Overview');
        $response->assertSee('Site Settings');
    }

    public function test_sidebar_excludes_news_and_subscribers_tabs(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertOk();
        $response->assertDontSee('/admin/news');
        $response->assertDontSee('/admin/subscribers');
    }
}
