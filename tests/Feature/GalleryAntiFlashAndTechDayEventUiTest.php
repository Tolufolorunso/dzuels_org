<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventSponsor;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryAntiFlashAndTechDayEventUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_has_anti_flash_protection_and_fixed_aspect_containers(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Student Projects 2026',
            'slug' => 'student-projects-2026',
            'is_active' => true,
            'is_public' => true,
            'sort_order' => 1,
        ]);

        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'front/images/techday.webp',
            'caption' => 'Robotics showcase in Ijero',
            'alt_text' => 'Students presenting a robotics prototype',
            'year' => 2026,
            'is_public' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/gallery');

        $response->assertStatus(200);
        // Verify x-cloak inline critical style is present
        $response->assertSee('[x-cloak]', false);
        $response->assertSee('display: none !important', false);
        // Verify deterministic 4:3 aspect container is used to prevent layout reflow
        $response->assertSee('aspect-4/3', false);
        // Verify smooth fade-in onload classes
        $response->assertSee('opacity-0', false);
        $response->assertSee("onload=\"this.classList.remove('opacity-0')\"", false);
    }

    public function test_event_model_dynamic_status_attributes_calculate_accurately(): void
    {
        $pastEvent = Event::create([
            'title' => 'Ijero Tech Day 2026',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Past flagship innovation festival in Ijero-Ekiti',
            'description' => 'Detailed description of the completed event',
            'event_date' => Carbon::now()->subDays(20),
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'is_registration_open' => false,
            'is_active' => true,
        ]);

        $futureEvent = Event::create([
            'title' => 'Ekiti Youth Coding Summit 2027',
            'slug' => 'ekiti-youth-coding-summit-2027',
            'summary' => 'Upcoming coding bootcamp for high school students',
            'description' => 'Future gathering description',
            'event_date' => Carbon::now()->addMonths(3),
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'is_registration_open' => true,
            'is_active' => true,
        ]);

        $todayEvent = Event::create([
            'title' => 'Community Digital Clinic',
            'slug' => 'community-digital-clinic',
            'summary' => 'Happening right now in Ijero-Ekiti',
            'description' => 'Clinic details',
            'event_date' => Carbon::today()->addHours(10),
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'is_registration_open' => true,
            'is_active' => true,
        ]);

        // Past event assertions
        $this->assertTrue($pastEvent->is_past);
        $this->assertFalse($pastEvent->is_future);
        $this->assertFalse($pastEvent->is_today);
        $this->assertSame('Concluded Edition', $pastEvent->status_label);
        $this->assertStringContainsString('Concluded', $pastEvent->edition_badge);
        $this->assertStringNotContainsString('Upcoming', $pastEvent->edition_badge);

        // Future event assertions
        $this->assertFalse($futureEvent->is_past);
        $this->assertTrue($futureEvent->is_future);
        $this->assertFalse($futureEvent->is_today);
        $this->assertSame('Upcoming Gathering', $futureEvent->status_label);
        $this->assertStringContainsString('Upcoming', $futureEvent->edition_badge);

        // Today event assertions
        $this->assertFalse($todayEvent->is_past);
        $this->assertFalse($todayEvent->is_future);
        $this->assertTrue($todayEvent->is_today);
        $this->assertSame('Happening Today', $todayEvent->status_label);
    }

    public function test_concluded_tech_day_event_page_does_not_display_upcoming_flagship(): void
    {
        $pastTechDay = Event::create([
            'title' => 'Ijero Tech Day 2026',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Annual innovation and tech gathering in Ijero-Ekiti',
            'description' => "Celebration of student tech builders.\n\nKeynote and lab exhibitions.",
            'objective' => 'Foster digital literacy and robotics for Ekiti secondary students',
            'event_date' => Carbon::create(2026, 10, 3, 10, 0, 0),
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'banner_image' => 'front/images/techday.webp',
            'is_registration_open' => false,
            'is_active' => true,
        ]);

        EventSponsor::create([
            'event_id' => $pastTechDay->id,
            'name' => 'Ekiti Tech Hub',
            'tier' => 'Platinum',
            'sort_order' => 1,
        ]);

        $response = $this->get(route('events.show', $pastTechDay->slug));

        $response->assertStatus(200);
        // Header should display concluded status
        $response->assertSee('Concluded Edition', false);
        // It must NOT hardcode "(Upcoming Flagship)" for concluded 2026 edition
        $response->assertDontSee('(Upcoming Flagship)', false);
        // Celebration summary card should be rendered
        $response->assertSee('Festival Summary & Impact', false);
        $response->assertSee('Edition Milestones Achieved', false);
        $response->assertSee('Cohort 2026 Certification', false);
        $response->assertSee('School Delegations', false);
        // Countdown timer should NOT be present for past event
        $response->assertDontSee('Countdown to Flagship Gathering', false);
        // Social share strip with 1-click copy link
        $response->assertSee('Copy Link', false);
    }

    public function test_future_event_page_displays_countdown_timer_and_upcoming_status(): void
    {
        $futureTechDay = Event::create([
            'title' => 'Ijero Tech Day 2027',
            'slug' => 'ijero-tech-day-2027',
            'summary' => 'Upcoming 4th edition of Ijero Tech Day',
            'description' => 'Future innovation festival.',
            'event_date' => Carbon::now()->addMonths(6),
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'is_registration_open' => true,
            'is_active' => true,
        ]);

        $response = $this->get(route('events.show', $futureTechDay->slug));

        $response->assertStatus(200);
        // Should display countdown timer for future events
        $response->assertSee('Countdown to Flagship Gathering', false);
        $response->assertSee('Event Begins In:', false);
    }

    public function test_event_show_page_renders_valid_schema_org_json_ld(): void
    {
        $event = Event::create([
            'title' => 'Ijero Tech Day 2026',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Premier youth technology festival',
            'description' => 'Description of the event.',
            'event_date' => Carbon::create(2026, 10, 3, 9, 30, 0),
            'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'is_registration_open' => false,
            'is_active' => true,
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        // Schema.org Event JSON-LD checks
        $response->assertSee('"@type": "Event"', false);
        $response->assertSee('"eventStatus": "https://schema.org/EventScheduled"', false);
        $response->assertSee('"eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode"', false);
        // It must NOT use EventMovedOnline as a past status
        $response->assertDontSee('EventMovedOnline', false);
        $response->assertSee('"name": "Dzuels Educational Foundation"', false);
    }

    public function test_events_index_page_displays_dynamic_status_and_edition_badges(): void
    {
        $itd2024 = Event::create([
            'title' => 'Ijero Tech Day 2024',
            'slug' => 'ijero-tech-day-2024',
            'summary' => 'Inaugural edition',
            'description' => 'Inaugural tech day gathering description.',
            'event_date' => Carbon::create(2024, 10, 5, 10, 0, 0),
            'is_active' => true,
        ]);

        $itd2026 = Event::create([
            'title' => 'Ijero Tech Day 2026',
            'slug' => 'ijero-tech-day-2026',
            'summary' => '3rd edition',
            'description' => '3rd edition celebration description.',
            'event_date' => Carbon::create(2026, 10, 3, 10, 0, 0),
            'is_active' => true,
        ]);

        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertSee('Ijero Tech Day (ITD) Multi-Year Editions', false);
        $response->assertSee('2024 • Inaugural', false);
        $response->assertSee('2026 • Concluded', false);
        // Must NOT hardcode 'Flagship (Upcoming)' on concluded 2026
        $response->assertDontSee('Flagship (Upcoming)', false);
    }
}
