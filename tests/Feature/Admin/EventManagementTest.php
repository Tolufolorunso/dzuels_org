<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventManagementTest extends TestCase
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
        $response = $this->get('/admin/events');
        $response->assertRedirect('/admin/login');

        $response = $this->get('/admin/events/create');
        $response->assertRedirect('/admin/login');

        $response = $this->post('/admin/events', []);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_events_index_with_registration_counts(): void
    {
        $event = Event::create([
            'title' => 'Ijero Tech Day 2026',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Annual technology celebration.',
            'description' => 'Full write-up of the event.',
            'venue' => 'Civic Centre, Ijero-Ekiti',
            'sort_order' => 1,
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        $event->registrations()->create([
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '08012345678',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/events');

        $response->assertOk();
        $response->assertSeeText('Events & Gatherings');
        $response->assertSeeText('Ijero Tech Day 2026');
        $response->assertSeeText('/events/ijero-tech-day-2026');
        $response->assertSeeText('Civic Centre, Ijero-Ekiti');
        $response->assertSeeText('1'); // Registration count
    }

    public function test_admin_can_create_event_with_uploaded_banner_and_schedule(): void
    {
        $banner = UploadedFile::fake()->image('event_banner.webp', 1200, 600);

        $response = $this->actingAs($this->admin)->post('/admin/events', [
            'title' => 'Robotics Bootcamp 2026',
            'slug' => 'robotics-bootcamp-2026',
            'summary' => '3-day hands-on robotics clinic for secondary school students.',
            'description' => 'Detailed curriculum covering Arduino sensors and motor controllers.',
            'event_date' => '2026-11-10 09:00',
            'venue' => 'Dzuels Hub, Ijero-Ekiti',
            'banner_image' => $banner,
            'schedule' => [
                ['time' => '09:00 AM - 10:00 AM', 'title' => 'Introduction to Circuits', 'speaker' => 'Lead Engineer'],
                ['time' => '10:15 AM - 12:30 PM', 'title' => 'Hands-on Breadboarding', 'speaker' => 'SeedDev Instructors'],
            ],
            'is_registration_open' => '1',
            'is_active' => '1',
            'sort_order' => '3',
            'seo_title' => 'Robotics Bootcamp | Dzuels',
            'seo_description' => 'Learn robotics in Ijero-Ekiti.',
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Event created successfully.');

        $this->assertDatabaseHas('events', [
            'title' => 'Robotics Bootcamp 2026',
            'slug' => 'robotics-bootcamp-2026',
            'venue' => 'Dzuels Hub, Ijero-Ekiti',
            'is_registration_open' => true,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $event = Event::where('slug', 'robotics-bootcamp-2026')->first();
        $this->assertNotNull($event->banner_image);
        $this->assertStringStartsWith('uploads/events/', $event->banner_image);
        $this->assertIsArray($event->schedule);
        $this->assertCount(2, $event->schedule);

        // Clean up created fake file
        if (file_exists(public_path($event->banner_image))) {
            @unlink(public_path($event->banner_image));
        }
    }

    public function test_admin_can_update_event(): void
    {
        $event = Event::create([
            'title' => 'Draft Event',
            'slug' => 'draft-event',
            'summary' => 'Initial event summary',
            'description' => 'Initial event narrative',
            'venue' => 'Old Venue',
            'is_registration_open' => false,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.events.update', $event), [
            'title' => 'Finalized Event',
            'slug' => 'finalized-event',
            'summary' => 'Updated summary text',
            'description' => 'Updated full description',
            'venue' => 'New Civic Center',
            'is_registration_open' => '1',
            'is_active' => '1',
            'sort_order' => '1',
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Event updated successfully.');

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Finalized Event',
            'slug' => 'finalized-event',
            'venue' => 'New Civic Center',
            'is_registration_open' => true,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_toggle_event_active_and_registration_status(): void
    {
        $event = Event::create([
            'title' => 'Toggle Status Event',
            'slug' => 'toggle-status-event',
            'summary' => 'Summary text',
            'description' => 'Description text',
            'is_active' => true,
            'is_registration_open' => true,
        ]);

        // Toggle active
        $response = $this->actingAs($this->admin)->patch(route('admin.events.toggle-active', $event));
        $response->assertRedirect(route('admin.events.index'));
        $this->assertFalse($event->fresh()->is_active);

        // Toggle registration
        $response = $this->actingAs($this->admin)->patch(route('admin.events.toggle-registration', $event));
        $response->assertRedirect(route('admin.events.index'));
        $this->assertFalse($event->fresh()->is_registration_open);

        // Toggle registration back
        $this->actingAs($this->admin)->patch(route('admin.events.toggle-registration', $event));
        $this->assertTrue($event->fresh()->is_registration_open);
    }

    public function test_admin_can_delete_event_and_cascades_registrations(): void
    {
        $event = Event::create([
            'title' => 'Event To Remove',
            'slug' => 'event-to-remove',
            'summary' => 'Summary',
            'description' => 'Description',
        ]);

        $reg = $event->registrations()->create([
            'full_name' => 'Registered Attendee',
            'email' => 'attendee@example.com',
            'phone' => '08099887766',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.events.destroy', $event));

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        $this->assertDatabaseMissing('event_registrations', ['id' => $reg->id]);
    }

    public function test_admin_can_view_event_registrations(): void
    {
        $event = Event::create([
            'title' => 'Flagship Conference',
            'slug' => 'flagship-conference',
            'summary' => 'Summary',
            'description' => 'Description',
        ]);

        $event->registrations()->create([
            'full_name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'phone' => '08033445566',
            'attendee_type' => 'student',
            'notes' => 'Looking forward to the coding clinic.',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.events.registrations', $event));

        $response->assertOk();
        $response->assertSeeText('Attendee Registrations: Flagship Conference');
        $response->assertSeeText('Sarah Johnson');
        $response->assertSeeText('sarah@example.com');
        $response->assertSeeText('08033445566');
        $response->assertSeeText('student');
        $response->assertSeeText('Looking forward to the coding clinic.');
    }

    public function test_admin_can_delete_event_registration(): void
    {
        $event = Event::create([
            'title' => 'Summit 2026',
            'slug' => 'summit-2026',
            'summary' => 'Summary',
            'description' => 'Description',
        ]);

        $registration = $event->registrations()->create([
            'full_name' => 'Cancel Me',
            'email' => 'cancel@example.com',
            'phone' => '08011223344',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.events.registrations.destroy', [$event, $registration]));

        $response->assertRedirect(route('admin.events.registrations', $event));
        $this->assertDatabaseMissing('event_registrations', ['id' => $registration->id]);
    }

    public function test_event_validation_requires_title_summary_and_description(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/events', [
            'title' => '',
            'summary' => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'summary', 'description']);
    }
}
