<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\EventSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GivingChannelCorrectionAndTechDayEventCenteredUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::clearCache();
    }

    public function test_donate_page_has_no_mock_wire_accounts_and_displays_why_donate_to_our_cause(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();

        // 1. Mock wire transfer text and domiciliary account numbers are absent
        $response->assertDontSee('International Wire Transfers', false);
        $response->assertDontSee('0450072881', false);
        $response->assertDontSee('0450073060', false);
        $response->assertDontSee('0450072898', false);
        $response->assertDontSee('GTBINGLA', false);

        // 2. Inspiring "Why Donate to Our Cause" card is present
        $response->assertSeeText('Why Donate to Our Cause');
        $response->assertSeeText('measurable learning opportunities for indigent children in Ekiti State');
        $response->assertSeeText('100% Direct Learner Benefit');
        $response->assertSeeText('Zero Administrative Deductions');
        $response->assertSeeText('CAC Certified Non-Profit Standing');
        $response->assertSeeText('Verified Progress & Photo Updates');

        // 3. Legitimate verified channels remain prominent
        $response->assertSeeText('Direct Bank Transfer');
        $response->assertSeeText('0450072850');
        $response->assertSeeText('Guaranty Trust Bank');
        $response->assertSeeText('U.S. Diaspora Giving');
        $response->assertSee('https://givebutter.com/dzuels-foundation', false);
    }

    public function test_tech_day_event_show_page_renders_centered_festival_showcase_layout(): void
    {
        $this->seed(EventSeeder::class);

        $response = $this->get('/events/ijero-tech-day-2026');

        $response->assertOk();

        // Centered hero layout assertions
        $response->assertSee('max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10 text-center', false);
        $response->assertSee('text-3xl sm:text-4xl lg:text-5xl font-black text-[var(--heading)] tracking-tight leading-tight max-w-4xl mx-auto', false);
        $response->assertSee('flex flex-wrap items-center justify-center gap-3', false);
        $response->assertSee('flex flex-wrap items-center justify-center gap-6 text-sm', false);

        // Festival Impact Metrics Bar centered
        $response->assertSee('grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-[var(--border)] max-w-3xl mx-auto', false);
        $response->assertSeeText('300+');
        $response->assertSeeText('Student Delegates');
        $response->assertSeeText('22');
        $response->assertSeeText('Cohort Graduates');

        // Multi-Year Edition Switcher
        $response->assertSeeText('Switch Festival Edition:');
        $response->assertSee(route('events.show', 'ijero-tech-day-2026'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2025'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2024'));

        // Main content centered container
        $response->assertSee('max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12', false);

        // Milestone Summary & Impact 3-column celebration card
        $response->assertSeeText('Festival Summary & Impact');
        $response->assertSeeText('Edition Milestones Achieved');
        $response->assertSeeText('Cohort 2026 Certification');
        $response->assertSeeText('School Delegations');
        $response->assertSeeText('Staggered Arrival Times');

        // Quick Event Facts Strip
        $response->assertSeeText('Event At a Glance');
        $response->assertSeeText('100% Free Sponsored');

        // Authentic Photography & Social Share
        $response->assertSeeText('ITD 2026 Authentic Photography');
        $response->assertSeeText('Spread the Word & Share:');
        $response->assertSeeText('Copy Link');
    }

    public function test_all_tech_day_editions_render_centered_showcases(): void
    {
        $this->seed(EventSeeder::class);

        foreach (['ijero-tech-day-2024', 'ijero-tech-day-2025', 'ijero-tech-day-2026'] as $slug) {
            $response = $this->get("/events/{$slug}");
            $response->assertOk();
            $response->assertSee('max-w-5xl mx-auto', false);
            $response->assertSeeText('Switch Festival Edition:');
        }
    }
}
