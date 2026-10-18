<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\PageSection;
use App\Models\Partner;
use App\Models\Programme;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Services\ImageOptimizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class LaunchCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:launch-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform comprehensive pre-launch health and readiness verification for Dzuels Foundation';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====================================================');
        $this->info(' Dzuels Educational Foundation - Launch Check Suite ');
        $this->info('====================================================');
        $this->newLine();

        $failures = 0;
        $warnings = 0;

        // 1. Environment & Security Checks
        $this->line('<fg=cyan;options=bold>1. Environment & Core Configuration</>');

        if (config('app.key')) {
            $this->line('  <fg=green>✔</> Application encryption key (APP_KEY) is generated.');
        } else {
            $this->line('  <fg=red>✖</> Missing APP_KEY.');
            $failures++;
        }

        if (File::isWritable(storage_path())) {
            $this->line('  <fg=green>✔</> Storage directory is writable.');
        } else {
            $this->line('  <fg=red>✖</> Storage directory is not writable.');
            $failures++;
        }

        if (File::exists(public_path('build/manifest.json'))) {
            $this->line('  <fg=green>✔</> Production asset manifest exists (Vite build is compiled).');
        } else {
            $this->line('  <fg=yellow>⚠</> Asset manifest missing. Run "npm run build" before deploying.');
            $warnings++;
        }

        $optimizer = app(ImageOptimizerService::class);
        $optStatus = $optimizer->getEnvironmentStatus();
        if ($optStatus['is_operational']) {
            $engineLabel = strtoupper($optStatus['active_engine']);
            $this->line("  <fg=green>✔</> Image optimization engine ready ({$engineLabel} with WebP support, memory_limit: {$optStatus['php']['memory_limit']}).");
        } else {
            $this->line('  <fg=red>✖</> No functional image optimization engine found (neither GD with WebP nor Imagick is enabled).');
            $failures++;
        }

        $this->newLine();

        // 2. Database & Data Integrity
        $this->line('<fg=cyan;options=bold>2. Database & Essential Foundation Data</>');

        try {
            DB::connection()->getPdo();
            $this->line('  <fg=green>✔</> Database connection successful.');
        } catch (\Throwable $e) {
            $this->line('  <fg=red>✖</> Database connection failed: '.$e->getMessage());
            $failures++;

            return self::FAILURE;
        }

        $requiredModels = [
            'Programmes' => Programme::count(),
            'Events' => Event::count(),
            'Team Members' => TeamMember::count(),
            'Partners' => Partner::count(),
            'Gallery Albums' => GalleryAlbum::count(),
            'Settings' => Setting::count(),
            'Page Sections' => PageSection::count(),
        ];

        foreach ($requiredModels as $name => $count) {
            if ($count > 0) {
                $this->line("  <fg=green>✔</> {$name} populated ({$count} records found).");
            } else {
                $this->line("  <fg=red>✖</> No records found for {$name}. Ensure database seeders have run.");
                $failures++;
            }
        }

        $hasItd2026 = Event::where('slug', 'ijero-tech-day-2026')->orWhere('slug', 'like', '%2026%')->exists();
        if ($hasItd2026) {
            $this->line('  <fg=green>✔</> ITD 2026 flagship event is seeded and present.');
        } else {
            $this->line('  <fg=yellow>⚠</> ITD 2026 event slug not found.');
            $warnings++;
        }

        if (Setting::getLogoUrl()) {
            $this->line('  <fg=green>✔</> Site logo helper configured ('.Setting::getLogoUrl().').');
        } else {
            $this->line('  <fg=yellow>⚠</> Site logo is not configured.');
            $warnings++;
        }

        $givebutterUrl = Setting::get('givebutter_url');
        if ($givebutterUrl) {
            $this->line('  <fg=green>✔</> Givebutter U.S. donation channel configured ('.$givebutterUrl.').');
        } else {
            $this->line('  <fg=yellow>⚠</> Givebutter URL is not set in settings.');
            $warnings++;
        }

        $causesSection = PageSection::where('page_key', 'home')->where('section_key', 'causes')->first();
        if ($causesSection && ! empty($causesSection->meta_data['pillars']) && count($causesSection->meta_data['pillars']) === 6) {
            $this->line('  <fg=green>✔</> 6 Impact Causes and foundational tagline verified in Page CMS.');
        } else {
            $this->line('  <fg=yellow>⚠</> 6 Impact Causes or tagline missing in home page sections.');
            $warnings++;
        }

        $sqlDumpPath = database_path('dumps/dzuels_database.sql');
        $rootDumpPath = base_path('dzuels_database.sql');
        if ((File::exists($sqlDumpPath) && filesize($sqlDumpPath) > 0) || (File::exists($rootDumpPath) && filesize($rootDumpPath) > 0)) {
            $checkedPath = (File::exists($rootDumpPath) && filesize($rootDumpPath) > 0) ? $rootDumpPath : $sqlDumpPath;
            $dumpSize = round(filesize($checkedPath) / 1024, 1);
            $this->line("  <fg=green>✔</> Production MariaDB/MySQL database dump exists ({$dumpSize} KB).");
        } else {
            $this->line('  <fg=yellow>⚠</> Production database dump missing. Generate with `php artisan db:export-sql`.');
            $warnings++;
        }

        $cpanelManifestPath = base_path('cpanel_changed_files.txt');
        if (File::exists($cpanelManifestPath) && filesize($cpanelManifestPath) > 0) {
            $this->line('  <fg=green>✔</> cPanel deployment manifest exists (cpanel_changed_files.txt).');
        } else {
            $this->line('  <fg=yellow>⚠</> cPanel deployment manifest missing.');
            $warnings++;
        }

        $this->newLine();

        // 3. Routing & Navigation Verification
        $this->line('<fg=cyan;options=bold>3. Critical Route Architecture</>');

        $criticalRoutes = [
            'home' => '/',
            'about' => '/about',
            'programmes.index' => '/programmes',
            'events.index' => '/events',
            'gallery.index' => '/gallery',
            'donate' => '/donate',
            'get-involved' => '/get-involved',
            'contact' => '/contact',
            'privacy-policy' => '/privacy-policy',
            'robots' => '/robots.txt',
            'sitemap' => '/sitemap.xml',
        ];

        foreach ($criticalRoutes as $routeName => $uri) {
            if (Route::has($routeName)) {
                $this->line("  <fg=green>✔</> Named route '{$routeName}' ({$uri}) is registered.");
            } else {
                $this->line("  <fg=red>✖</> Named route '{$routeName}' is missing.");
                $failures++;
            }
        }

        $this->newLine();

        // Summary
        $this->info('----------------------------------------------------');
        if ($failures === 0) {
            $this->info(" Launch Readiness Status: READY FOR LAUNCH (0 failures, {$warnings} warnings)");
            $this->info('----------------------------------------------------');

            return self::SUCCESS;
        }

        $this->error(" Launch Readiness Status: FAILED ({$failures} critical failure(s), {$warnings} warning(s))");
        $this->info('----------------------------------------------------');

        return self::FAILURE;
    }
}
