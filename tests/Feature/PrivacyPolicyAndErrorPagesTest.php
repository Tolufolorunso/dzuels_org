<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyAndErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_page_returns_200_and_renders_governance_sections(): void
    {
        $response = $this->get('/privacy-policy');

        $response->assertOk();
        $response->assertSee('Privacy Policy | Dzuels Educational Foundation', false);
        $response->assertSee('<link rel="canonical" href="'.url('/privacy-policy').'">', false);
        $response->assertSeeText('CAC/IT/NO 123456');
        $response->assertSeeText('Organization Identity & Scope');
        $response->assertSeeText('Information We Collect');
        $response->assertSeeText('Flutterwave');
        $response->assertSeeText('Your Rights Under Data Protection Laws');
        $response->assertSeeText('AAoJ Memorial Learning Center, Ijero-Ekiti');

        // Verify Breadcrumbs JSON-LD is rendered
        $content = $response->getContent();
        $this->assertStringContainsString('"@type": "BreadcrumbList"', $content);
        $this->assertStringContainsString('Privacy Policy', $content);
    }

    public function test_footer_contains_active_link_to_privacy_policy(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(url('/privacy-policy'));
        $response->assertSeeText('Privacy Policy');
    }

    public function test_public_intake_forms_include_privacy_policy_notices(): void
    {
        // 1. Contact Form
        $contactResponse = $this->get('/contact');
        $contactResponse->assertOk();
        $contactResponse->assertSee(route('privacy-policy'));
        $contactResponse->assertSeeText('Privacy Policy');

        // 2. Get Involved Page (Volunteer and Partner forms)
        $getInvolvedResponse = $this->get('/get-involved');
        $getInvolvedResponse->assertOk();
        $getInvolvedResponse->assertSee(route('privacy-policy'));
        $getInvolvedResponse->assertSeeText('Volunteer applications are processed strictly in accordance with our');
        $getInvolvedResponse->assertSeeText('Partnership proposals are handled strictly in accordance with our');

        // 3. Event Registration Form
        $event = Event::create([
            'title' => 'Sample Community Gathering',
            'slug' => 'sample-community-gathering',
            'summary' => 'Event summary',
            'description' => '<p>Event description</p>',
            'event_date' => now()->addWeeks(2),
            'location' => 'Ijero-Ekiti',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        $eventResponse = $this->get('/events/'.$event->slug);
        $eventResponse->assertOk();
        $eventResponse->assertSee(route('privacy-policy'));
        $eventResponse->assertSeeText('Attendee details are protected under our');
    }

    public function test_non_existent_page_renders_custom_branded_404_page_with_home_and_donate_links(): void
    {
        $response = $this->get('/non-existent-page-path-12345');

        $response->assertNotFound();
        $response->assertSee('404');
        $response->assertSeeText('Page Not Found');
        $response->assertSeeText("We Couldn't Find That Page");

        // Verify recovery links to Home and Donate
        $response->assertSee('href="'.url('/').'"', false);
        $response->assertSeeText('Return to Homepage');
        $response->assertSee('href="'.url('/donate').'"', false);
        $response->assertSeeText('Support With a Donation');
    }

    public function test_500_error_view_exists_and_contains_home_and_donate_links(): void
    {
        $renderedView = view('errors.500')->render();

        $this->assertStringContainsString('500', $renderedView);
        $this->assertStringContainsString('Something Went Unexpectedly Wrong', $renderedView);
        $this->assertStringContainsString('Temporary Technical Issue', $renderedView);
        $this->assertStringContainsString(url('/'), $renderedView);
        $this->assertStringContainsString(url('/donate'), $renderedView);
        $this->assertStringContainsString('Return to Homepage', $renderedView);
        $this->assertStringContainsString('info@dzuelsfoundation.org', $renderedView);
        $this->assertStringContainsString('+234 904 249 3883', $renderedView);
    }

    public function test_sitemap_xml_includes_privacy_policy(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee(url('/privacy-policy'));
    }
}
