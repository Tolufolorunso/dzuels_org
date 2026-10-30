<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Setting;
use Database\Seeders\EventSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IjeroTechDayEventShowPageRedesignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::clearCache();
    }

    public function test_itd_2026_renders_with_homepage_aligned_layout_and_elements(): void
    {
        $this->seed(EventSeeder::class);

        $response = $this->get('/events/ijero-tech-day-2026');

        $response->assertOk();

        // 1. Full-bleed rich hero with dark burgundy/navy gradient
        $response->assertSee('relative bg-maroon text-white', false);
        $response->assertSee('linear-gradient(135deg, rgba(43, 2, 3, 0.94) 0%, rgba(106, 4, 6, 0.85) 55%, rgba(36, 47, 64, 0.72) 100%)', false);
        $response->assertSeeText('Ijero Tech Day 2026');

        // Dynamic status badge (October 2026 concluded)
        $response->assertSeeText('Concluded Edition');

        // Breadcrumb in hero
        $response->assertSee('aria-label="Breadcrumb"', false);
        $response->assertSeeText('Home');
        $response->assertSeeText('Events');

        // Multi-year edition switcher inside hero
        $response->assertSeeText('Switch Festival Edition:');
        $response->assertSee(route('events.show', 'ijero-tech-day-2026'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2025'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2024'));

        // 2. Floating 4-metric banner matching homepage style
        $response->assertSee('relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 mb-8', false);
        $response->assertSeeText('300');
        $response->assertSeeText('Student Delegates across Ijero-Ekiti');
        $response->assertSeeText('22');
        $response->assertSeeText('Certified Cohort Graduates');
        $response->assertSeeText('10');
        $response->assertSeeText('Interactive Hands-on Stations');
        $response->assertSeeText('100');
        $response->assertSeeText('Free & Fully Sponsored Admission');

        // 3. Balanced 2-column story & media section (offset cream border)
        $response->assertSee('border-3 border-[var(--accent-cream)] rounded-3xl', false);
        $response->assertSee('grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center', false);
        $response->assertSeeText('Story & Context');
        $response->assertSeeText('About Ijero Tech-Day 2026 (ITD 2026)');
        $response->assertSeeText('Key Event Objective');

        // 4. 3-column milestone celebration card
        $response->assertSeeText('Festival Summary & Impact');
        $response->assertSeeText('Edition Milestones Achieved');
        $response->assertSeeText('Cohort 2026 Certification');
        $response->assertSeeText('School Delegations');
        $response->assertSeeText('Staggered Arrival Times');

        // 5. Interactive agenda & schedule timeline
        $response->assertSeeText('Agenda & Schedule');
        $response->assertSeeText('Event Schedule Highlights');
        $response->assertSeeText('Accreditation, Batch Allocation & Welcome Assembly');

        // 6. Authentic photo gallery and social share
        $response->assertSeeText('ITD 2026 Authentic Photography');
        $response->assertSeeText('Spread the Word & Share:');
        $response->assertSeeText('Copy Link');

        // 7. Full-bleed radial maroon CTA section
        $response->assertSee('bg-radial from-crimson/40 via-maroon to-[#2B0203]', false);
        $response->assertSeeText('Empower Future Innovators in Ijero-Ekiti');
        $response->assertSeeText('Support Our Mission');
    }

    public function test_all_three_tech_day_editions_render_homepage_aligned_layout(): void
    {
        $this->seed(EventSeeder::class);

        foreach (['ijero-tech-day-2024', 'ijero-tech-day-2025', 'ijero-tech-day-2026'] as $slug) {
            $response = $this->get("/events/{$slug}");
            $response->assertOk();

            // Verify full-bleed hero & gradient
            $response->assertSee('relative bg-maroon text-white', false);
            // Verify floating 4-metric banner
            $response->assertSee('relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 mb-8', false);
            // Verify balanced 2-column section with offset cream border
            $response->assertSee('border-3 border-[var(--accent-cream)] rounded-3xl', false);
            // Verify edition switcher
            $response->assertSeeText('Switch Festival Edition:');
            // Verify radial CTA
            $response->assertSee('bg-radial from-crimson/40 via-maroon to-[#2B0203]', false);
        }
    }

    public function test_non_tech_day_event_renders_balanced_layout_gracefully(): void
    {
        $event = Event::create([
            'title' => 'Ekiti Teachers Digital Workshop',
            'slug' => 'ekiti-teachers-digital-workshop',
            'summary' => 'Capacity building workshop for public school educators.',
            'description' => 'Comprehensive digital literacy and curriculum integration workshop.',
            'event_date' => now()->addMonth(),
            'venue' => 'Dzuels Foundation Computer Lab, Ijero-Ekiti',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        $response = $this->get('/events/ekiti-teachers-digital-workshop');

        $response->assertOk();
        $response->assertSeeText('Ekiti Teachers Digital Workshop');
        $response->assertSee('relative bg-maroon text-white', false);
        $response->assertSee('border-3 border-[var(--accent-cream)] rounded-3xl', false);
        $response->assertSeeText('Register for this Event');
        $response->assertSee('bg-radial from-crimson/40 via-maroon to-[#2B0203]', false);
    }

    public function test_future_tech_day_event_displays_upcoming_flagship_and_countdown(): void
    {
        $futureTechDay = Event::create([
            'title' => 'Ijero Tech Day 2027',
            'slug' => 'ijero-tech-day-2027',
            'summary' => 'Next annual festival gathering for youth innovation.',
            'description' => 'Detailed description of future tech day.',
            'event_date' => now()->addMonths(6),
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'is_registration_open' => true,
            'is_active' => true,
        ]);

        $response = $this->get('/events/ijero-tech-day-2027');

        $response->assertOk();
        $response->assertSeeText('Upcoming Flagship');
        $response->assertSeeText('Countdown to Flagship Gathering');
        $response->assertSeeText('Event Begins In:');
        $response->assertSeeText('Register Free');
    }
}
