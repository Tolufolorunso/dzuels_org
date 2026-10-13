<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\PartnerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PartnerSeeder::class);
    }

    public function test_homepage_renders_successfully_with_all_core_sections(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        // Hero Section
        $response->assertSee('Dzuels Educational Foundation');
        $response->assertSeeText('Addressing poverty through quality');
        $response->assertSeeText('education');
        $response->assertSeeText('Donate Now');
        $response->assertSeeText('Become a Volunteer');

        // Impact Statistics Band
        $response->assertSeeText('Ekiti students in ICT training');
        $response->assertSeeText('Ekiti students in literacy intervention');
        $response->assertSeeText('Schools Supported');

        // Who We Are Section
        $response->assertSeeText('Who We Are');
        $response->assertSeeText('Transforming lives page by page');
        $response->assertSeeText('Read Our Full Story');

        // Vision & Mission Cards
        $response->assertSeeText('Our Vision');
        $response->assertSeeText('To alleviate poverty through quality education and lifelong skills acquisition.');
        $response->assertSeeText('Our Mission');
        $response->assertSeeText('To make quality education accessible to every child within reach, regardless of circumstance.');

        // DRNICER Values Band
        $response->assertSeeText('The DRNICER Principles');
        $response->assertSeeText('Discipline');
        $response->assertSeeText('Responsibility');
        $response->assertSeeText('No-Excuse');
        $response->assertSeeText('Integrity');
        $response->assertSeeText('Compassion');
        $response->assertSeeText('Excellence');
        $response->assertSeeText('Respect');

        // Our 6 Authentic Causes Section
        $response->assertSeeText('Our 6 Impact Causes');
        $response->assertSeeText('AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD');
        $response->assertSeeText('6 Foundational Pillars');
        $response->assertSeeText('Education Support');
        $response->assertSeeText("Paul's academy");
        $response->assertSeeText('Skills Acquisition');
        $response->assertSeeText("Peter's net");
        $response->assertSeeText('Advocacy');
        $response->assertSeeText('Deborah congress');
        $response->assertSeeText('Feeding families');
        $response->assertSeeText("Naomi's kitchen");
        $response->assertSeeText('Transformative leadership Training');
        $response->assertSeeText("Priscilla's upper room");
        $response->assertSeeText('Health awareness and support');
        $response->assertSeeText("Luke's Tent");

        // Featured Project Card
        $response->assertSeeText('Featured Project');
        $response->assertSeeText('Digital Infrastructure & Literacy in Ijero-Ekiti');
        $response->assertSeeText('Learn More About Project');
        $response->assertSeeText('Volunteer for ITD');

        // Upcoming Event Callout
        $response->assertSeeText('Upcoming Flagship Event • Oct 17, 2026');
        $response->assertSeeText('Ijero Tech-Day 2026 (ITD 2026)');
        $response->assertSeeText('Register to Attend');

        // Partners Strip
        $response->assertSeeText('Our Valued Partners');
        $response->assertSeeText('Collaborating for greater impact');
        $response->assertSee('ASCEND Education Foundation');
        $response->assertSee('Solape Adesuyi Associates');
        $response->assertSee('Help Meet A Need');
        $response->assertSee('Int International');

        // Final CTA Banner
        $response->assertSeeText('You too can make a difference');
        $response->assertSeeText('Make a Donation');
        $response->assertSeeText('Join as a Volunteer');
    }

    public function test_homepage_displays_dynamic_impact_statistics_from_settings(): void
    {
        Setting::set('stat_ict_students', 350, 'integer');
        Setting::set('stat_literacy_students', 820, 'integer');
        Setting::set('stat_schools_supported', 25, 'integer');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('350');
        $response->assertSee('820');
        $response->assertSee('25');
    }

    public function test_homepage_falls_back_to_verified_defaults_when_settings_empty(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('165');
        $response->assertSee('591');
        $response->assertSee('15');
    }

    public function test_homepage_contains_working_links_to_key_pages(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('/donate');
        $response->assertSee('/get-involved');
        $response->assertSee('/about');
        $response->assertSee('/programmes');
        $response->assertSee('/events');
    }
}
