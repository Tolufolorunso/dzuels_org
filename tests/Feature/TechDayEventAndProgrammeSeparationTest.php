<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\GalleryAlbum;
use Database\Seeders\EventSeeder;
use Database\Seeders\ProgrammeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechDayEventAndProgrammeSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_programme_separation_excludes_tech_day_from_continuous_programmes_list_and_renders_flagship_spotlight(): void
    {
        $this->seed(ProgrammeSeeder::class);

        $response = $this->get('/programmes');

        $response->assertOk();
        $response->assertSeeText('Continuous Educational Initiatives');
        $response->assertSeeText('Annual Flagship Festival');
        $response->assertSeeText('Ijero Tech Day (ITD)');
        $response->assertSee(route('events.show', 'ijero-tech-day-2026'));

        // Tech Day must not appear in continuous programmes cards
        $response->assertDontSee('/programmes/innovation-and-tech-day');
    }

    public function test_visiting_programme_tech_day_slug_returns_301_redirect_to_flagship_event(): void
    {
        $response = $this->get('/programmes/innovation-and-tech-day');
        $response->assertRedirect(route('events.show', 'ijero-tech-day-2026'));
        $this->assertEquals(301, $response->getStatusCode());

        $aliasResponse = $this->get('/programmes/itd');
        $aliasResponse->assertRedirect(route('events.show', 'ijero-tech-day-2026'));
        $this->assertEquals(301, $aliasResponse->getStatusCode());
    }

    public function test_events_table_contains_all_three_editions_with_authentic_attributes(): void
    {
        $this->seed(EventSeeder::class);

        $itd2026 = Event::where('slug', 'ijero-tech-day-2026')->first();
        $this->assertNotNull($itd2026);
        $this->assertEquals('2026-10-03', $itd2026->event_date->format('Y-m-d'));
        $this->assertEquals('Learning Today, Creating Tomorrow', $itd2026->theme);
        $this->assertNotEmpty($itd2026->banner_image);

        $itd2025 = Event::where('slug', 'ijero-tech-day-2025')->first();
        $this->assertNotNull($itd2025);
        $this->assertEquals('2025-10-25', $itd2025->event_date->format('Y-m-d'));
        $this->assertEquals('Empowering Rural Youth Through Innovation & Technology', $itd2025->theme);
        $this->assertNotEmpty($itd2025->banner_image);

        $itd2024 = Event::where('slug', 'ijero-tech-day-2024')->first();
        $this->assertNotNull($itd2024);
        $this->assertEquals('2024-11-15', $itd2024->event_date->format('Y-m-d'));
        $this->assertEquals('Igniting Digital Potential in Ijero-Ekiti', $itd2024->theme);
        $this->assertNotEmpty($itd2024->banner_image);
    }

    public function test_tech_day_event_show_page_renders_multi_year_edition_switcher(): void
    {
        $this->seed(EventSeeder::class);

        // 2026 Edition
        $response2026 = $this->get('/events/ijero-tech-day-2026');
        $response2026->assertOk();
        $response2026->assertSeeText('Switch Festival Edition');
        $response2026->assertSee(route('events.show', 'ijero-tech-day-2026'));
        $response2026->assertSee(route('events.show', 'ijero-tech-day-2025'));
        $response2026->assertSee(route('events.show', 'ijero-tech-day-2024'));
        $response2026->assertSeeText('Upcoming Flagship');

        // 2025 Edition
        $response2025 = $this->get('/events/ijero-tech-day-2025');
        $response2025->assertOk();
        $response2025->assertSeeText('Switch Festival Edition');
        $response2025->assertSeeText('2nd Edition');

        // 2024 Edition
        $response2024 = $this->get('/events/ijero-tech-day-2024');
        $response2024->assertOk();
        $response2024->assertSeeText('Switch Festival Edition');
        $response2024->assertSeeText('Inaugural');
    }

    public function test_events_index_renders_multi_year_editions_strip(): void
    {
        $this->seed(EventSeeder::class);

        $response = $this->get('/events');
        $response->assertOk();
        $response->assertSeeText('Ijero Tech Day (ITD) Multi-Year Editions');
        $response->assertSee(route('events.show', 'ijero-tech-day-2026'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2025'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2024'));
        $response->assertSeeText('Flagship (Upcoming)');
        $response->assertSeeText('2nd Edition');
        $response->assertSeeText('Inaugural');
    }

    public function test_purge_command_runs_successfully(): void
    {
        $this->artisan('media:purge-legacy-techday', ['--dry-run' => true])
            ->assertSuccessful();
    }

    public function test_authentic_tech_day_photos_render_on_show_page_when_present(): void
    {
        $this->seed(EventSeeder::class);

        $album = GalleryAlbum::create([
            'title' => 'ITD 2026',
            'slug' => 'itd-2026',
            'is_active' => true,
            'is_public' => true,
        ]);

        $photo = $album->photos()->create([
            'image_path' => 'uploads/media/techday/2026/test_sample_2026.webp',
            'caption' => 'ITD 2026 Robotics Demo',
            'alt_text' => 'Robotics exhibition booth at ITD 2026',
            'sort_order' => 10,
            'year' => 2026,
        ]);

        $response = $this->get('/events/ijero-tech-day-2026');
        $response->assertOk();
        $response->assertSeeText('ITD 2026 Authentic Photography');
        $response->assertSee('test_sample_2026.webp');
    }
}
