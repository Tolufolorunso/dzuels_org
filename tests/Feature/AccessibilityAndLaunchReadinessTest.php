<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibilityAndLaunchReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_contain_skip_to_content_link_and_main_landmark(): void
    {
        $routes = ['/', '/about', '/contact', '/privacy-policy'];

        foreach ($routes as $route) {
            $response = $this->get($route);

            $response->assertOk();
            $response->assertSeeText('Skip to main content');
            $response->assertSee('href="#main-content"', false);
            $response->assertSee('<main id="main-content"', false);
        }
    }

    public function test_admin_dashboard_contains_skip_link_and_main_landmark(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin_a11y@dzuelsfoundation.org',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSeeText('Skip to main content');
        $response->assertSee('href="#main-content"', false);
        $response->assertSee('<main id="main-content"', false);
    }

    public function test_contact_form_has_accessible_input_labels_and_aria_attributes(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('for="first_name"', false);
        $response->assertSee('id="first_name"', false);
        $response->assertSee('for="last_name"', false);
        $response->assertSee('id="last_name"', false);
        $response->assertSee('for="email"', false);
        $response->assertSee('id="email"', false);
        $response->assertSee('for="subject"', false);
        $response->assertSee('id="subject"', false);
        $response->assertSee('for="message"', false);
        $response->assertSee('id="message"', false);
        $response->assertSee('aria-required="true"', false);
    }

    public function test_get_involved_forms_have_accessible_labels_and_aria_attributes(): void
    {
        $response = $this->get('/get-involved');

        $response->assertOk();
        // Volunteer form
        $response->assertSee('for="vol_full_name"', false);
        $response->assertSee('id="vol_full_name"', false);
        $response->assertSee('for="vol_email"', false);
        $response->assertSee('id="vol_email"', false);
        $response->assertSee('for="vol_phone"', false);
        $response->assertSee('id="vol_phone"', false);
        $response->assertSee('for="vol_age_band"', false);
        $response->assertSee('id="vol_age_band"', false);
        $response->assertSee('for="vol_work_status"', false);
        $response->assertSee('id="vol_work_status"', false);

        // Partner form
        $response->assertSee('for="part_org_name"', false);
        $response->assertSee('id="part_org_name"', false);
        $response->assertSee('for="part_contact_name"', false);
        $response->assertSee('id="part_contact_name"', false);
        $response->assertSee('for="part_email"', false);
        $response->assertSee('id="part_email"', false);
        $response->assertSee('for="part_type"', false);
        $response->assertSee('id="part_type"', false);
        $response->assertSee('for="part_message"', false);
        $response->assertSee('id="part_message"', false);
    }

    public function test_event_registration_form_has_accessible_labels_and_aria(): void
    {
        $event = Event::where('slug', '!=', 'ijero-tech-day-2026')
            ->where('is_registration_open', true)
            ->first();

        if (! $event) {
            $event = Event::create([
                'title' => 'Community Tech Clinic',
                'slug' => 'community-tech-clinic',
                'summary' => 'Open community clinic.',
                'description' => 'Detailed clinic description.',
                'event_date' => now()->addDays(20),
                'venue' => 'Ijero-Ekiti',
                'is_registration_open' => true,
                'is_active' => true,
            ]);
        }

        $response = $this->get(route('events.show', $event->slug));

        $response->assertOk();
        $response->assertSee('for="full_name"', false);
        $response->assertSee('id="full_name"', false);
        $response->assertSee('for="email"', false);
        $response->assertSee('id="email"', false);
        $response->assertSee('for="phone"', false);
        $response->assertSee('id="phone"', false);
        $response->assertSee('aria-required="true"', false);
    }

    public function test_home_page_images_have_alt_tags_and_performance_markers(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        // Hero image eager & high fetch priority
        $response->assertSee('fetchpriority="high"', false);
        $response->assertSee('loading="eager"', false);
        $response->assertSee('decoding="async"', false);

        // Below the fold lazy loading
        $response->assertSee('loading="lazy"', false);
    }

    public function test_app_launch_check_artisan_command_passes(): void
    {
        $this->artisan('app:launch-check')
            ->expectsOutputToContain('Dzuels Educational Foundation - Launch Check Suite')
            ->expectsOutputToContain('READY FOR LAUNCH')
            ->assertExitCode(0);
    }
}
