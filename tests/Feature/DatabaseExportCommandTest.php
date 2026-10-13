<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventSponsor;
use App\Models\Setting;
use App\Models\User;
use App\Services\DatabaseExportService;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DatabaseExportCommandTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $savedDefaultDumpContent = null;

    protected ?string $savedRootDumpContent = null;

    protected function setUp(): void
    {
        parent::setUp();

        $defaultDump = database_path('dumps/dzuels_database.sql');
        if (File::exists($defaultDump)) {
            $this->savedDefaultDumpContent = File::get($defaultDump);
        }

        $rootDump = base_path('dzuels_database.sql');
        if (File::exists($rootDump)) {
            $this->savedRootDumpContent = File::get($rootDump);
        }
    }

    protected function tearDown(): void
    {
        // Clean up test dumps if created
        $customDump = base_path('database/dumps/test_export.sql');
        if (File::exists($customDump)) {
            File::delete($customDump);
        }

        // Restore original default dump if backed up
        if ($this->savedDefaultDumpContent !== null) {
            File::put(database_path('dumps/dzuels_database.sql'), $this->savedDefaultDumpContent);
        }

        if ($this->savedRootDumpContent !== null) {
            File::put(base_path('dzuels_database.sql'), $this->savedRootDumpContent);
        }

        parent::tearDown();
    }

    public function test_it_runs_db_export_sql_command_successfully_with_default_output(): void
    {
        $defaultDumpPath = database_path('dumps/dzuels_database.sql');
        $rootDumpPath = base_path('dzuels_database.sql');

        $this->artisan('db:export-sql')
            ->expectsOutputToContain('Dzuels Foundation - MySQL Database Export Suite')
            ->expectsOutputToContain('Successfully exported')
            ->assertSuccessful();

        $this->assertFileExists($defaultDumpPath);
        $this->assertGreaterThan(0, filesize($defaultDumpPath));
        $this->assertFileExists($rootDumpPath);
        $this->assertGreaterThan(0, filesize($rootDumpPath));
    }

    public function test_it_exports_to_custom_output_path(): void
    {
        $customRelative = 'database/dumps/test_export.sql';
        $customAbsolute = base_path($customRelative);

        $this->artisan('db:export-sql', ['--output' => $customRelative])
            ->expectsOutputToContain('Target Output:')
            ->assertSuccessful();

        $this->assertFileExists($customAbsolute);
        $this->assertGreaterThan(0, filesize($customAbsolute));
    }

    public function test_it_generates_valid_mysql_directives_header_and_footer(): void
    {
        $customRelative = 'database/dumps/test_export.sql';
        $customAbsolute = base_path($customRelative);

        $this->artisan('db:export-sql', ['--output' => $customRelative])
            ->assertSuccessful();

        $content = File::get($customAbsolute);

        // Header directives
        $this->assertStringContainsString('SET FOREIGN_KEY_CHECKS=0;', $content);
        $this->assertStringContainsString('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";', $content);
        $this->assertStringContainsString('START TRANSACTION;', $content);
        $this->assertStringContainsString('SET NAMES utf8mb4', $content);

        // Footer directives
        $this->assertStringContainsString('COMMIT;', $content);
        $this->assertStringContainsString('SET FOREIGN_KEY_CHECKS=1;', $content);
    }

    public function test_it_includes_schema_and_table_ddl_for_core_tables(): void
    {
        $customRelative = 'database/dumps/test_export.sql';
        $customAbsolute = base_path($customRelative);

        $this->artisan('db:export-sql', ['--output' => $customRelative])
            ->assertSuccessful();

        $content = File::get($customAbsolute);

        $expectedTables = [
            'migrations',
            'users',
            'settings',
            'events',
            'event_sponsors',
            'programmes',
            'gallery_albums',
            'gallery_photos',
            'team_members',
            'partners',
            'contact_messages',
            'volunteer_applications',
            'page_sections',
        ];

        foreach ($expectedTables as $table) {
            $this->assertStringContainsString("DROP TABLE IF EXISTS `{$table}`;", $content);
            $this->assertStringContainsString("CREATE TABLE `{$table}` (", $content);
            $this->assertStringContainsString('ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;', $content);
        }
    }

    public function test_it_dumps_populated_table_rows_with_safe_inserts(): void
    {
        // Seed test records
        User::create([
            'name' => 'Test Admin',
            'email' => 'export_test@dzuelsfoundation.org',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Setting::set('test_site_name', 'Dzuels Foundation Export Test', 'text');

        $event = Event::create([
            'title' => 'Test Tech Day',
            'slug' => 'test-tech-day',
            'summary' => 'Test summary with special characters: "quotes" & \'apostrophes\' and newlines.\nLine 2.',
            'description' => 'Full description',
        ]);

        EventSponsor::create([
            'event_id' => $event->id,
            'name' => 'Acme Sponsor',
            'tier' => 'lead',
        ]);

        $customRelative = 'database/dumps/test_export.sql';
        $customAbsolute = base_path($customRelative);

        $this->artisan('db:export-sql', ['--output' => $customRelative])
            ->assertSuccessful();

        $content = File::get($customAbsolute);

        // Verify users insert
        $this->assertStringContainsString('INSERT INTO `users`', $content);
        $this->assertStringContainsString('export_test@dzuelsfoundation.org', $content);

        // Verify settings insert
        $this->assertStringContainsString('INSERT INTO `settings`', $content);
        $this->assertStringContainsString('test_site_name', $content);

        // Verify events insert with escaping
        $this->assertStringContainsString('INSERT INTO `events`', $content);
        $this->assertStringContainsString('test-tech-day', $content);

        // Verify foreign key in event_sponsors
        $this->assertStringContainsString('INSERT INTO `event_sponsors`', $content);
        $this->assertStringContainsString('Acme Sponsor', $content);
        $this->assertStringContainsString('CONSTRAINT `fk_event_sponsors_event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE', $content);
    }

    public function test_service_returns_accurate_export_statistics(): void
    {
        $service = app(DatabaseExportService::class);
        $customAbsolute = base_path('database/dumps/test_export.sql');

        $result = $service->export($customAbsolute);

        $this->assertIsArray($result);
        $this->assertEquals($customAbsolute, $result['path']);
        $this->assertGreaterThan(0, $result['bytes']);
        $this->assertIsArray($result['tables']);
        $this->assertArrayHasKey('users', $result['tables']);
        $this->assertArrayHasKey('migrations', $result['tables']);
        $this->assertArrayHasKey('page_sections', $result['tables']);
    }

    public function test_it_exports_page_sections_with_meta_data(): void
    {
        $this->seed(PageSectionSeeder::class);

        $customRelative = 'database/dumps/test_export.sql';
        $customAbsolute = base_path($customRelative);

        $this->artisan('db:export-sql', ['--output' => $customRelative])
            ->assertSuccessful();

        $content = File::get($customAbsolute);

        $this->assertStringContainsString('INSERT INTO `page_sections`', $content);
        $this->assertStringContainsString('AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD', $content);
        $this->assertStringContainsString('Paul\\\'s academy', $content);
    }
}
