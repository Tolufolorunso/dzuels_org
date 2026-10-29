<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use App\Services\ImageOptimizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Finder\Finder;

class IngestTechDayPhotosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:ingest-techday
                            {--year= : Specific year to ingest (2024, 2025, or 2026)}
                            {--limit=0 : Maximum number of photos to ingest per year (0 for all)}
                            {--delete-source : Delete source files in final/ after optimization (default false: keep originals)}
                            {--force : Re-optimize and re-catalog photos even if previously cataloged}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest, optimize (<= 180 KB WebP), and catalog authentic Tech Day photography from final/ into 2024, 2025, and 2026 editions';

    /**
     * Execute the console command.
     */
    public function handle(ImageOptimizerService $optimizer): int
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(0);

        $targetYear = $this->option('year') ? (int) $this->option('year') : null;
        $limit = (int) $this->option('limit');
        $deleteSource = (bool) $this->option('delete-source');
        $force = (bool) $this->option('force');

        $this->info('====================================================');
        $this->info(' Dzuels Foundation - Curated Tech Day Ingestion     ');
        $this->info('====================================================');
        $this->line('Engine: <comment>'.$optimizer->getActiveEngine().'</comment> (strict <= 180 KB WebP)');
        $this->line('Preserve final/ source archives: <comment>'.($deleteSource ? 'NO (deleting source)' : 'YES (safe)').'</comment>');
        $this->newLine();

        $yearsConfig = [
            2024 => [
                'folder' => 'final/techday2024',
                'album_slug' => 'itd-2024',
                'album_title' => 'ITD 2024 (Inaugural Edition)',
                'kicker' => 'Inaugural Tech Day',
                'description' => 'Milestone highlights and student innovation from the inaugural Ijero Tech Day held on November 15, 2024.',
                'sort_order' => 10,
                'dest_dir' => 'uploads/media/techday/2024',
                'event_slug' => 'ijero-tech-day-2024',
            ],
            2025 => [
                'folder' => 'final/techday2025',
                'album_slug' => 'itd-2025',
                'album_title' => 'ITD 2025 (2nd Annual Edition)',
                'kicker' => '2nd Annual Tech Day',
                'description' => 'Student hackathons, exhibitions, and digital presentations from the 2nd annual Ijero Tech Day on October 25, 2025.',
                'sort_order' => 11,
                'dest_dir' => 'uploads/media/techday/2025',
                'event_slug' => 'ijero-tech-day-2025',
            ],
            2026 => [
                'folder' => 'final/techday2026',
                'album_slug' => 'itd-2026',
                'album_title' => 'ITD 2026 (Flagship Innovation Festival)',
                'kicker' => 'Flagship Innovation Festival',
                'description' => 'Official media archive and youth technology festival presentations for Ijero Tech-Day 2026 (ITD 2026).',
                'sort_order' => 12,
                'dest_dir' => 'uploads/media/techday/2026',
                'event_slug' => 'ijero-tech-day-2026',
            ],
        ];

        if ($targetYear) {
            if (! isset($yearsConfig[$targetYear])) {
                $this->error("Invalid year [{$targetYear}]. Supported years are: 2024, 2025, 2026.");

                return self::FAILURE;
            }
            $yearsConfig = [$targetYear => $yearsConfig[$targetYear]];
        }

        $grandTotalProcessed = 0;
        $grandTotalSkipped = 0;
        $grandTotalErrors = 0;
        $grandTotalBytesSaved = 0;

        foreach ($yearsConfig as $year => $config) {
            $this->info('----------------------------------------------------');
            $this->info(" Processing Tech Day {$year} from {$config['folder']}");
            $this->info('----------------------------------------------------');

            $folderPath = base_path($config['folder']);
            if (! File::isDirectory($folderPath)) {
                $this->warn("Source folder does not exist: {$folderPath}. Skipping.");

                continue;
            }

            // Ensure destination folder exists
            $destPath = public_path($config['dest_dir']);
            if (! File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }

            // 1. Ensure Album exists
            $album = GalleryAlbum::firstOrCreate(
                ['slug' => $config['album_slug']],
                [
                    'title' => $config['album_title'],
                    'kicker' => $config['kicker'],
                    'description' => $config['description'],
                    'is_public' => true,
                    'is_active' => true,
                    'sort_order' => $config['sort_order'],
                ]
            );

            // Update album attributes to match latest branding
            $album->update([
                'title' => $config['album_title'],
                'kicker' => $config['kicker'],
                'description' => $config['description'],
                'is_public' => true,
                'is_active' => true,
            ]);

            // 2. Discover images in folder
            $finder = new Finder;
            $finder->files()
                ->in($folderPath)
                ->name('/\.(jpe?g|png|webp)$/i')
                ->sortByName();

            $files = iterator_to_array($finder, false);
            $totalDiscovered = count($files);
            $this->line("Discovered <comment>{$totalDiscovered}</comment> photos for {$year}.");

            if ($limit > 0 && $totalDiscovered > $limit) {
                $files = array_slice($files, 0, $limit);
                $this->line("Limiting to first {$limit} photos.");
            }

            $bar = $this->output->createProgressBar(count($files));
            $bar->start();

            $yearProcessed = 0;
            $yearSkipped = 0;
            $yearErrors = 0;
            $firstOptimizedPath = null;

            foreach ($files as $index => $file) {
                $filePath = $file->getRealPath();
                $origFilename = $file->getFilename();
                $origSize = $file->getSize();
                $cleanName = pathinfo($origFilename, PATHINFO_FILENAME);

                // Deduplication check
                if (! $force) {
                    $existingItem = MediaItem::where('caption', 'like', "%[{$origFilename}]%")
                        ->where('year', $year)
                        ->first();

                    if ($existingItem) {
                        $yearSkipped++;
                        if (! $firstOptimizedPath) {
                            $firstOptimizedPath = $existingItem->file_path;
                        }
                        $bar->advance();

                        continue;
                    }
                }

                $humanTitle = "ITD {$year} - {$cleanName}";

                try {
                    // Optimize strictly <= 180 KB WebP
                    $optimizedRelativePath = $optimizer->optimize(
                        $filePath,
                        $config['dest_dir'],
                        1920,
                        1920,
                        82,
                        ImageOptimizerService::MAX_BYTES,
                        $deleteSource
                    );

                    $fullOptimizedPath = public_path($optimizedRelativePath);
                    $finalSize = File::exists($fullOptimizedPath) ? File::size($fullOptimizedPath) : 0;
                    $dimensions = @getimagesize($fullOptimizedPath);

                    // Create MediaItem
                    $mediaItem = MediaItem::create([
                        'filename' => basename($optimizedRelativePath),
                        'file_path' => $optimizedRelativePath,
                        'file_size' => $finalSize,
                        'mime_type' => 'image/webp',
                        'width' => $dimensions[0] ?? null,
                        'height' => $dimensions[1] ?? null,
                        'title' => $humanTitle,
                        'caption' => "{$humanTitle} [{$origFilename}]",
                        'alt_text' => "Dzuels Foundation Ijero Tech Day {$year} photo {$cleanName}",
                        'source_type' => 'gallery',
                        'year' => $year,
                        'gallery_album_id' => $album->id,
                        'gallery_photo_id' => null,
                    ]);

                    // Create GalleryPhoto
                    $maxOrder = (int) GalleryPhoto::where('gallery_album_id', $album->id)->max('sort_order');
                    $photo = GalleryPhoto::create([
                        'gallery_album_id' => $album->id,
                        'image_path' => $optimizedRelativePath,
                        'title' => $humanTitle,
                        'caption' => "Ijero Tech Day {$year} Highlights",
                        'alt_text' => $humanTitle,
                        'sort_order' => $maxOrder + 10,
                        'year' => $year,
                    ]);

                    $mediaItem->update(['gallery_photo_id' => $photo->id]);

                    if (! $album->cover_photo_id) {
                        $album->update(['cover_photo_id' => $photo->id]);
                    }

                    if (! $firstOptimizedPath) {
                        $firstOptimizedPath = $optimizedRelativePath;
                    }

                    $yearProcessed++;
                    if ($origSize > $finalSize) {
                        $grandTotalBytesSaved += ($origSize - $finalSize);
                    }
                } catch (\Throwable $e) {
                    $yearErrors++;
                    Log::error("Tech Day {$year} ingestion failed for {$filePath}: ".$e->getMessage());
                }

                // Explicit garbage collection every 10 images to maintain low memory profile
                if (($index + 1) % 10 === 0) {
                    gc_collect_cycles();
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            $grandTotalProcessed += $yearProcessed;
            $grandTotalSkipped += $yearSkipped;
            $grandTotalErrors += $yearErrors;

            $this->info("Completed {$year}: {$yearProcessed} ingested, {$yearSkipped} skipped, {$yearErrors} errors.");

            // Update Event banner_image if appropriate
            if ($firstOptimizedPath) {
                $event = Event::where('slug', $config['event_slug'])->first();
                if ($event) {
                    // Update banner image for 2024 and 2025 to authentic photo
                    // For 2026, retain backdrop if set, or set to authentic photo if empty
                    if ($year !== 2026 || empty($event->banner_image)) {
                        $event->update(['banner_image' => $firstOptimizedPath]);
                        $this->line("Updated Event [{$event->slug}] banner_image to: <comment>{$firstOptimizedPath}</comment>");
                    }
                }
            }
        }

        $this->newLine();
        $savedMb = number_format($grandTotalBytesSaved / 1048576, 2);
        $this->info('====================================================');
        $this->info(' Tech Day Photos Ingestion Complete!                ');
        $this->info(" Total Processed & Cataloged: {$grandTotalProcessed}");
        $this->info(" Total Skipped: {$grandTotalSkipped}");
        $this->info(" Total Errors: {$grandTotalErrors}");
        $this->info(" Storage Saved Through WebP: {$savedMb} MB");
        $this->info('====================================================');

        return self::SUCCESS;
    }
}
