<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Programme;
use Database\Seeders\EventSeeder;
use Database\Seeders\PageSectionSeeder;
use Database\Seeders\ProgrammeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MediaIntegrationAndProductionLaunchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PageSectionSeeder::class);
        $this->seed(ProgrammeSeeder::class);
        $this->seed(EventSeeder::class);
    }

    /**
     * Test Tech Day flagship separation from Programmes index and legacy slug redirect.
     */
    public function test_programmes_index_excludes_tech_day_and_redirects_legacy_slugs(): void
    {
        $response = $this->get('/programmes');
        $response->assertStatus(200);

        // Tech Day must not appear in normal programmes list
        $response->assertDontSee('Ijero Tech Day 2026');

        // Legacy programme slugs must 301 redirect to the flagship event
        $legacySlugs = [
            'innovation-and-tech-day',
            'itd',
            'ijero-tech-day',
        ];

        foreach ($legacySlugs as $slug) {
            $redirectResponse = $this->get("/programmes/{$slug}");
            $redirectResponse->assertStatus(301);
            $redirectResponse->assertRedirect('/events/ijero-tech-day-2026');
        }
    }

    /**
     * Test events index lists all 3 multi-year editions (2024, 2025, 2026).
     */
    public function test_events_index_showcases_multi_year_tech_day_editions(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);

        $response->assertSee('Ijero Tech-Day 2026');
        $response->assertSee('Ijero Tech Day 2025');
        $response->assertSee('Ijero Tech Day 2024');
    }

    /**
     * Test event show page renders edition switcher pills and photo preview.
     */
    public function test_event_show_page_renders_edition_switcher(): void
    {
        $event2026 = Event::where('slug', 'ijero-tech-day-2026')->firstOrFail();

        $response = $this->get("/events/{$event2026->slug}");
        $response->assertStatus(200);

        $response->assertSeeText('Switch Festival Edition:');
        $response->assertSee(route('events.show', 'ijero-tech-day-2026'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2025'));
        $response->assertSee(route('events.show', 'ijero-tech-day-2024'));
    }

    /**
     * Test public homepage loads with optimized assets and valid meta.
     */
    public function test_homepage_loads_successfully_with_optimized_brand_assets(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('Addressing poverty through quality education');
        $response->assertSee('6 Foundational Pillars');
    }

    /**
     * Test public gallery loads with curated collections and filter tabs.
     */
    public function test_gallery_index_renders_authentic_collections(): void
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200);

        $response->assertSee('Curated Visual Archive');
        $response->assertSee('All Collections');
    }

    /**
     * Test database export command dumps complete schema and data including media_items.
     */
    public function test_database_export_command_includes_media_items_and_event_data(): void
    {
        $customDump = 'database/dumps/test_launch_verification.sql';
        $fullPath = base_path($customDump);

        try {
            $this->artisan('db:export-sql', ['--output' => $customDump])
                ->expectsOutputToContain('Dzuels Foundation - MySQL Database Export Suite')
                ->assertSuccessful();

            $this->assertFileExists($fullPath);
            $content = File::get($fullPath);

            // Verify core SQL compatibility directives
            $this->assertStringContainsString('SET FOREIGN_KEY_CHECKS=0;', $content);
            $this->assertStringContainsString('SET NAMES utf8mb4', $content);
            $this->assertStringContainsString('COMMIT;', $content);

            // Verify tables exist
            $this->assertStringContainsString('CREATE TABLE `events`', $content);
            $this->assertStringContainsString('CREATE TABLE `media_items`', $content);
            $this->assertStringContainsString('CREATE TABLE `gallery_albums`', $content);
            $this->assertStringContainsString('CREATE TABLE `gallery_photos`', $content);
            $this->assertStringContainsString('CREATE TABLE `page_sections`', $content);

            // Verify data rows
            $this->assertStringContainsString('INSERT INTO `events`', $content);
            $this->assertStringContainsString('ijero-tech-day-2026', $content);
            $this->assertStringContainsString('ijero-tech-day-2025', $content);
            $this->assertStringContainsString('ijero-tech-day-2024', $content);
        } finally {
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }

    /**
     * Test cPanel deployment manifest contains all Milestone 11 entries.
     */
    public function test_cpanel_manifest_contains_milestone_11_entries(): void
    {
        $manifestPath = base_path('cpanel_changed_files.txt');
        $this->assertFileExists($manifestPath);

        $manifest = File::get($manifestPath);

        // Feature 33
        $this->assertStringContainsString('app/Http/Controllers/Admin/MediaLibraryController.php', $manifest);
        $this->assertStringContainsString('resources/views/admin/media/index.blade.php', $manifest);

        // Feature 34
        $this->assertStringContainsString('app/Console/Commands/PurgeLegacyTechDayCommand.php', $manifest);
        $this->assertStringContainsString('app/Console/Commands/IngestTechDayPhotosCommand.php', $manifest);
        $this->assertStringContainsString('resources/views/events/show.blade.php', $manifest);

        // Feature 35
        $this->assertStringContainsString('app/Console/Commands/ExportDatabaseSql.php', $manifest);
        $this->assertStringContainsString('app/Services/DatabaseExportService.php', $manifest);
        $this->assertStringContainsString('dzuels_database.sql', $manifest);
        $this->assertStringContainsString('database/dumps/dzuels_database.sql', $manifest);
    }
}
