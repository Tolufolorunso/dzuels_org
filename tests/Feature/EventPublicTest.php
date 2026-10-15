<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_index_displays_active_events(): void
    {
        Event::create([
            'title' => 'Active Public Summit',
            'slug' => 'active-public-summit',
            'summary' => 'Open community technology summit.',
            'description' => 'Detailed description of the upcoming summit.',
            'event_date' => now()->addDays(20),
            'venue' => 'Ijero-Ekiti Civic Center',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        Event::create([
            'title' => 'Secret Private Draft',
            'slug' => 'secret-private-draft',
            'summary' => 'Hidden event.',
            'description' => 'Should not be visible to public.',
            'event_date' => now()->addDays(10),
            'is_active' => false,
        ]);

        $response = $this->get('/events');

        $response->assertOk();
        $response->assertSeeText('Active Public Summit');
        $response->assertSeeText('Open community technology summit.');
        $response->assertDontSeeText('Secret Private Draft');
    }

    public function test_public_event_detail_renders_with_banner_schedule_and_registration_form(): void
    {
        $event = Event::create([
            'title' => 'Annual Tech Summit 2026',
            'slug' => 'annual-tech-summit-2026',
            'summary' => 'Celebrating 10 years of technology impact.',
            'description' => 'A gathering of over 200 secondary school students in Ijero-Ekiti.',
            'event_date' => now()->addMonth(),
            'venue' => 'Dzuels Foundation Hub, Ijero-Ekiti',
            'banner_image' => 'front/images/techday.webp',
            'schedule' => [
                ['time' => '10:00 AM - 11:30 AM', 'title' => 'Keynote Presentation', 'speaker' => 'Lead Trustee'],
                ['time' => '11:30 AM - 01:00 PM', 'title' => 'Student Robotics Exhibition', 'speaker' => 'SeedDev Mentors'],
            ],
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        $response = $this->get('/events/annual-tech-summit-2026');

        $response->assertOk();
        $response->assertSeeText('Annual Tech Summit 2026');
        $response->assertSeeText('A gathering of over 200 secondary school students in Ijero-Ekiti.');
        $response->assertSeeText('Dzuels Foundation Hub, Ijero-Ekiti');
        $response->assertSeeText('10:00 AM - 11:30 AM');
        $response->assertSeeText('Keynote Presentation');
        $response->assertSeeText('Student Robotics Exhibition');
        $response->assertSeeText('Register for this Event');
    }

    public function test_public_registration_submission_creates_registration_record_and_flashes_success(): void
    {
        $event = Event::create([
            'title' => 'Open Registration Summit',
            'slug' => 'open-registration-summit',
            'summary' => 'Summary text',
            'description' => 'Description text',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        $response = $this->post('/events/open-registration-summit/register', [
            'full_name' => 'Fadekemi Michelle',
            'email' => 'fadekemi@example.com',
            'phone' => '08022334455',
            'attendee_type' => 'student',
            'notes' => 'Excited for the technology demos.',
        ]);

        $response->assertRedirect('/events/open-registration-summit');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'full_name' => 'Fadekemi Michelle',
            'email' => 'fadekemi@example.com',
            'phone' => '08022334455',
            'attendee_type' => 'student',
            'notes' => 'Excited for the technology demos.',
        ]);
    }

    public function test_public_registration_prevents_duplicate_registration_by_email(): void
    {
        $event = Event::create([
            'title' => 'Duplicate Prevention Gathering',
            'slug' => 'duplicate-prevention-gathering',
            'summary' => 'Summary',
            'description' => 'Description',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        // First registration succeeds
        $event->registrations()->create([
            'full_name' => 'Existing Attendee',
            'email' => 'duplicate@example.com',
            'phone' => '08011223344',
        ]);

        // Second registration with same email
        $response = $this->post('/events/duplicate-prevention-gathering/register', [
            'full_name' => 'Duplicate Try',
            'email' => 'duplicate@example.com',
            'phone' => '08099887766',
        ]);

        $response->assertRedirect('/events/duplicate-prevention-gathering');
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('event_registrations', 1);
    }

    public function test_public_registration_is_blocked_when_registration_closed(): void
    {
        Event::create([
            'title' => 'Closed Event',
            'slug' => 'closed-event',
            'summary' => 'Summary',
            'description' => 'Description',
            'is_active' => true,
            'is_registration_open' => false,
        ]);

        $response = $this->post('/events/closed-event/register', [
            'full_name' => 'Late Attendee',
            'email' => 'late@example.com',
            'phone' => '08011112222',
        ]);

        $response->assertRedirect('/events/closed-event');
        $response->assertSessionHas('error', 'Registration for this event is currently closed.');
        $this->assertDatabaseCount('event_registrations', 0);
    }

    public function test_inactive_event_returns_404_for_public_visitor(): void
    {
        Event::create([
            'title' => 'Hidden Event',
            'slug' => 'hidden-event',
            'summary' => 'Summary',
            'description' => 'Description',
            'is_active' => false,
        ]);

        $response = $this->get('/events/hidden-event');
        $response->assertNotFound();
    }

    public function test_empty_events_database_displays_friendly_fallback(): void
    {
        $response = $this->get('/events');

        $response->assertOk();
        $response->assertSeeText('No Upcoming Events Announced');
    }

    public function test_registration_validation_requires_full_name_email_and_phone(): void
    {
        Event::create([
            'title' => 'Validating Event',
            'slug' => 'validating-event',
            'summary' => 'Summary',
            'description' => 'Description',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        $response = $this->post('/events/validating-event/register', [
            'full_name' => '',
            'email' => 'invalid-email',
            'phone' => '',
        ]);

        $response->assertSessionHasErrors(['full_name', 'email', 'phone']);
    }

    public function test_event_registration_is_rate_limited_after_excessive_attempts(): void
    {
        $event = Event::create([
            'title' => 'Throttled Event',
            'slug' => 'throttled-event',
            'summary' => 'Summary',
            'description' => 'Description',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        for ($i = 0; $i < 6; $i++) {
            $this->post('/events/throttled-event/register', [
                'full_name' => "Attendee {$i}",
                'email' => "attendee{$i}@example.com",
                'phone' => "0801122334{$i}",
            ]);
        }

        $blocked = $this->post('/events/throttled-event/register', [
            'full_name' => 'Blocked Attendee',
            'email' => 'blocked@example.com',
            'phone' => '08099887766',
        ]);

        $blocked->assertStatus(429);
    }
}
