<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\Partner;
use App\Models\Programme;
use App\Models\Setting;
use App\Models\TeamMember;
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
