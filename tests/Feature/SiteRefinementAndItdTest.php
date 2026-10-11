<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteRefinementAndItdTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_and_footer_exclude_news_and_newsletter_form(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        // Header navigation does not contain /news
        $response->assertDontSee('href="'.url('/news').'"', false);
        // Footer does not contain newsletter form input
        $response->assertDontSee('newsletter/subscribe');
        $response->assertDontSee('Subscribe to our newsletter');
        // Footer contains "Get In Touch" touchpoint
        $response->assertSee('Get In Touch');
        $response->assertSee('Volunteer or Partner');
        $response->assertSee(url('/contact'));
        $response->assertSee(url('/get-involved'));
    }

    public function test_homepage_excludes_latest_news_section_even_with_published_posts(): void
    {
        Post::create([
            'title' => 'Important Community Update',
            'slug' => 'important-community-update',
            'excerpt' => 'This is a test post excerpt.',
            'body' => 'Full body content of test post.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSeeText('Latest News & Stories');
        $response->assertDontSeeText('Important Community Update');
    }

    public function test_homepage_teaser_links_to_itd_2026_with_theme_and_date(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Ijero Tech-Day 2026 (ITD 2026)');
        $response->assertSeeText('Learning Today, Creating Tomorrow');
        $response->assertSeeText('Oct 17, 2026');
        $response->assertSee(url('/events/ijero-tech-day-2026'));
    }

    public function test_itd_2026_event_page_renders_theme_venue_and_schedule(): void
    {
        $event = Event::create([
            'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Empowering 300 children with practical AI tools.',
            'description' => 'Flagship community technology festival held at AAoJ Memorial Learning Center.',
            'event_date' => '2026-10-17 08:00:00',
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)',
            'theme' => 'Learning Today, Creating Tomorrow',
            'schedule' => [
                [
                    'time' => '08:00 AM - 09:00 AM',
                    'title' => 'Accreditation, Batch Allocation & Welcome Assembly',
                    'speaker' => 'Dzuels Foundation Coordination Team',
                ],
                [
                    'time' => '09:00 AM - 09:30 AM',
                    'title' => 'Opening Ceremony: "Learning Today, Creating Tomorrow"',
                    'speaker' => 'Board of Trustees & Community Leaders',
                ],
                [
                    'time' => '04:30 PM - 05:00 PM',
                    'title' => 'Cohort Graduation: Presentation of 22 Certificates & Grand Finale',
                    'speaker' => 'Foundation Leadership & Dignitaries',
                ],
            ],
            'is_registration_open' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/events/ijero-tech-day-2026');

        $response->assertOk();
        $response->assertSeeText('Ijero Tech-Day 2026 (ITD 2026)');
        $response->assertSeeText('Learning Today, Creating Tomorrow');
        $response->assertSeeText('AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)');
        $response->assertSeeText('October 17, 2026');
        $response->assertSeeText('Accreditation, Batch Allocation & Welcome Assembly');
        $response->assertSeeText('Cohort Graduation: Presentation of 22 Certificates & Grand Finale');
        $response->assertSee('Student / Youth (Batch Participant)');
        $response->assertSee('Cohort Graduate (Certificate Recipient)');
    }

    public function test_attendee_can_register_for_itd_2026(): void
    {
        $event = Event::create([
            'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Empowering 300 children with practical AI tools.',
            'description' => 'Flagship community technology festival held at AAoJ Memorial Learning Center.',
            'event_date' => '2026-10-17 08:00:00',
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)',
            'theme' => 'Learning Today, Creating Tomorrow',
            'is_registration_open' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $postData = [
            'full_name' => 'Bamidele Adeleke',
            'email' => 'bamidele@example.com',
            'phone' => '08023456789',
            'attendee_type' => 'student',
            'notes' => 'Batch 3 participant from Community Grammar School',
        ];

        $response = $this->post('/events/ijero-tech-day-2026/register', $postData);

        $response->assertRedirect('/events/ijero-tech-day-2026');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'full_name' => 'Bamidele Adeleke',
            'email' => 'bamidele@example.com',
            'phone' => '08023456789',
            'attendee_type' => 'student',
            'notes' => 'Batch 3 participant from Community Grammar School',
        ]);
    }

    public function test_duplicate_registration_is_prevented(): void
    {
        $event = Event::create([
            'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Empowering 300 children with practical AI tools.',
            'description' => 'Flagship community technology festival held at AAoJ Memorial Learning Center.',
            'event_date' => '2026-10-17 08:00:00',
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)',
            'theme' => 'Learning Today, Creating Tomorrow',
            'is_registration_open' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        EventRegistration::create([
            'event_id' => $event->id,
            'full_name' => 'Existing Attendee',
            'email' => 'repeat@example.com',
            'phone' => '08011112222',
            'attendee_type' => 'student',
        ]);

        $response = $this->post('/events/ijero-tech-day-2026/register', [
            'full_name' => 'Existing Attendee',
            'email' => 'repeat@example.com',
            'phone' => '08011112222',
            'attendee_type' => 'student',
        ]);

        $response->assertRedirect('/events/ijero-tech-day-2026');
        $response->assertSessionHas('error');
        $this->assertEquals(1, EventRegistration::where('email', 'repeat@example.com')->count());
    }
}
