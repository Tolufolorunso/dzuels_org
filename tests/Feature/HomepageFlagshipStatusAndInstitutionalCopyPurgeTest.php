<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageFlagshipStatusAndInstitutionalCopyPurgeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::clearCache();
    }

    public function test_homepage_section_8_renders_recent_flagship_event_status_and_links_when_event_is_past(): void
    {
        Event::create([
            'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Empowering 300 children with practical AI tools across 10 rotating batches of 30.',
            'description' => 'Flagship community technology festival held at AAoJ Memorial Learning Center.',
            'event_date' => '2026-10-17 08:00:00',
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'theme' => 'Learning Today, Creating Tomorrow',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Recent Flagship Event • Oct 17, 2026');
        $response->assertSeeText('Highlights');
        $response->assertSeeText('Ijero Tech-Day 2026 (ITD 2026)');
        $response->assertSeeText('Learning Today, Creating Tomorrow');
        $response->assertSeeText('Explore Event Highlights');
        $response->assertSeeText('View Photo Gallery');
        $response->assertSee(url('/events/ijero-tech-day-2026'));
        $response->assertSee(url('/events/ijero-tech-day-2026#photo-gallery'));
        $response->assertDontSeeText('Register to Attend');
    }

    public function test_homepage_section_8_renders_upcoming_flagship_event_status_when_event_is_future(): void
    {
        $futureDate = now()->addMonths(2);

        Event::create([
            'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Empowering 300 children with practical AI tools across 10 rotating batches of 30.',
            'description' => 'Flagship community technology festival held at AAoJ Memorial Learning Center.',
            'event_date' => $futureDate,
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'theme' => 'Learning Today, Creating Tomorrow',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Upcoming Flagship Event • '.$futureDate->format('M j, Y'));
        $response->assertSeeText('Register to Attend');
        $response->assertDontSeeText('Explore Event Highlights');
        $response->assertDontSeeText('View Photo Gallery');
    }

    public function test_donate_page_purges_zero_administrative_deductions_and_displays_transparent_stewardship(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertDontSeeText('Zero Administrative Deductions');
        $response->assertDontSeeText('funded independently by our Board of Trustees');
        $response->assertDontSeeText('Public donations are never eaten by admin fees');
        $response->assertSeeText('Transparent Stewardship & Direct Impact');
        $response->assertSeeText('strict financial accountability and transparent reporting');
    }

    public function test_donate_page_purges_equips_public_school_computer_labs(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertDontSeeText('equips public school computer labs');
        $response->assertDontSeeText('rural public schools');
        $response->assertSeeText('sustains our computer lab and library at Dzuels Foundation in Ijero-Ekiti');
    }

    public function test_donate_controller_tiers_and_pillars_do_not_reference_public_schools(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertSeeText('Community Lab Terminal');
        $response->assertDontSeeText('School Lab Terminal');
        $response->assertSeeText('underprivileged students and community schools');
    }

    public function test_get_involved_page_mentorship_references_learning_center_not_public_schools(): void
    {
        $response = $this->get('/get-involved');

        $response->assertOk();
        $response->assertSeeText('directly to community students at our learning center');
        $response->assertDontSeeText('directly to public school students');
    }
}
