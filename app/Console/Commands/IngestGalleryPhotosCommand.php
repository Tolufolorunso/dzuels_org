<?php

namespace App\Console\Commands;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use App\Services\ImageOptimizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class IngestGalleryPhotosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:ingest-gallery
                            {--folder=gallery : Root folder or subfolder relative to project root}
                            {--limit=0 : Maximum number of photos to ingest (0 for all)}
                            {--skip-techday : Skip ITD and TechDay subfolders (handled from final/ in Feature 34)}
                            {--delete-source : Delete original source files after optimization (default false: keep originals intact)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest, optimize (<= 180 KB WebP), tag with collection and year, and catalog gallery archive photos';

    /**
     * Execute the console command.
     */
    public function handle(ImageOptimizerService $optimizer): int
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(0);

        $folderOption = trim($this->option('folder') ?: 'gallery');
        $limit = (int) $this->option('limit');
        $skipTechday = (bool) $this->option('skip-techday');
        $deleteSource = (bool) $this->option('delete-source');

        $basePath = base_path($folderOption);

        if (! File::isDirectory($basePath)) {
            $this->error("Directory does not exist: {$basePath}");

            return self::FAILURE;
        }

        $this->info('====================================================');
        $this->info(' Dzuels Foundation - Resilient Media Gallery Ingest  ');
        $this->info('====================================================');
        $this->line("Source directory: <comment>{$basePath}</comment>");
        $this->line('Engine: <comment>'.$optimizer->getActiveEngine().'</comment> (strict <= 180 KB WebP)');
        $this->line('Delete source files: <comment>'.($deleteSource ? 'YES' : 'NO (preserve archive)').'</comment>');
        $this->line('Skip Tech Day folders: <comment>'.($skipTechday ? 'YES (reserved for final/ in Feature 34)' : 'NO').'</comment>');
        $this->newLine();

        // Ensure target collections exist in database
        $albumMap = $this->ensureAlbumsExist();

        // Find all supported image files
        $finder = new Finder;
        $finder->files()
            ->in($basePath)
            ->name('/\.(jpe?g|png|webp|gif)$/i');

        $files = iterator_to_array($finder, false);
        $totalFound = count($files);

        $this->info("Discovered {$totalFound} total image candidates in {$folderOption}.");

        $eligibleFiles = [];
        foreach ($files as $file) {
            $relPath = str_replace('\\', '/', $file->getRelativePathname());

            if ($skipTechday) {
                $lower = strtolower($relPath);
                if (str_contains($lower, 'itd') || str_contains($lower, 'techday')) {
                    continue;
                }
            }

            $eligibleFiles[] = $file;
        }

        $totalEligible = count($eligibleFiles);
        $this->info("Eligible photos for ingestion: {$totalEligible}".($skipTechday ? " ({$totalFound} minus TechDay folders)" : ''));

        if ($limit > 0 && $totalEligible > $limit) {
            $eligibleFiles = array_slice($eligibleFiles, 0, $limit);
            $this->info("Limiting batch run to first {$limit} photos as requested.");
        }

        if (empty($eligibleFiles)) {
            $this->warn('No eligible photos to process.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($eligibleFiles));
        $bar->start();

        $processedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;
        $totalBytesSaved = 0;

        foreach ($eligibleFiles as $index => $file) {
            $filePath = $file->getRealPath();
            $origFilename = $file->getFilename();
            $origSize = $file->getSize();
            $relSubdir = str_replace('\\', '/', $file->getRelativePath());

            // Determine Collection & Year
            [$albumId, $defaultYear, $categoryPrefix] = $this->classifyPhoto($relSubdir, $origFilename, $albumMap);

            // Extract 4-digit year from filename if present
            $itemYear = $defaultYear;
            if (preg_match('/\b(202[0-9]|203[0-9])\b/', $origFilename, $ym)) {
                $itemYear = (int) $ym[1];
            }

            // Deduplication check: check if already cataloged by original filename or clean title
            $cleanName = pathinfo($origFilename, PATHINFO_FILENAME);
            $existingItem = MediaItem::where('caption', 'like', "%[{$origFilename}]%")
                ->orWhere('title', 'like', "%{$cleanName}%")
                ->first();

            if ($existingItem) {
                $skippedCount++;
                $bar->advance();

                continue;
            }
            $humanTitle = Str::title(str_replace(['-', '_'], ' ', $cleanName));
            if ($categoryPrefix && ! str_starts_with(strtolower($humanTitle), strtolower($categoryPrefix))) {
                $humanTitle = "{$categoryPrefix} - {$humanTitle}";
            }

            try {
                // Compress strictly into WebP (<= 180 KB)
                $optimizedRelativePath = $optimizer->optimize(
                    $filePath,
                    'uploads/media/gallery',
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
                    'caption' => "{$humanTitle} ({$itemYear}) [{$origFilename}]",
                    'alt_text' => "{$humanTitle} ({$itemYear})",
                    'source_type' => 'gallery',
                    'year' => $itemYear,
                    'gallery_album_id' => $albumId,
                    'gallery_photo_id' => null,
                ]);

                // Create GalleryPhoto link if album is active
                if ($albumId) {
                    $maxOrder = (int) GalleryPhoto::where('gallery_album_id', $albumId)->max('sort_order');
                    $photo = GalleryPhoto::create([
                        'gallery_album_id' => $albumId,
                        'image_path' => $optimizedRelativePath,
                        'title' => $humanTitle,
                        'caption' => "Dzuels Foundation Archive ({$itemYear})",
                        'alt_text' => $humanTitle,
                        'sort_order' => $maxOrder + 10,
                        'is_active' => true,
                    ]);

                    $mediaItem->update(['gallery_photo_id' => $photo->id]);
                }

                $processedCount++;
                if ($origSize > $finalSize) {
                    $totalBytesSaved += ($origSize - $finalSize);
                }
            } catch (\Throwable $e) {
                $errorCount++;
                Log::error("Gallery ingestion failed for {$filePath}: ".$e->getMessage());
            }

            // Periodic garbage collection to keep GD heap lightweight
            if (($index + 1) % 25 === 0) {
                gc_collect_cycles();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $savedMb = number_format($totalBytesSaved / 1048576, 2);
        $this->info('====================================================');
        $this->info(' Ingestion Complete!                                ');
        $this->info(" Processed & Cataloged: {$processedCount}");
        $this->info(" Skipped (already exist): {$skippedCount}");
        $this->info(" Errors: {$errorCount}");
        $this->info(" Storage saved through WebP compression: {$savedMb} MB");
        $this->info('====================================================');

        return self::SUCCESS;
    }

    /**
     * Map or create essential gallery albums for ingestion categorization.
     *
     * @return array<string, int>
     */
    protected function ensureAlbumsExist(): array
    {
        $albums = [
            'reading-competition' => [
                'title' => 'Reading & Children’s Library',
                'kicker' => 'Early Childhood Literacy',
                'description' => 'Children reading, participating in interactive storytelling sessions, and receiving literacy learning books.',
                'is_public' => true,
            ],
            'spelling-bee' => [
                'title' => 'Spelling Bee Championship',
                'kicker' => 'Academic Precision & Stage Confidence',
                'description' => 'Annual student spelling bees celebrating academic precision, vocabulary mastery, and public speaking confidence.',
                'is_public' => true,
            ],
            'general' => [
                'title' => 'General Community & Cohorts',
                'kicker' => 'Youth Mentorship & Excursions',
                'description' => 'Foundation youth community cohorts, field excursions, workshops, and educational celebrations.',
                'is_public' => true,
            ],
        ];

        $map = [];
        foreach ($albums as $slug => $data) {
            $album = GalleryAlbum::firstOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'sort_order' => 10,
                    'is_active' => true,
                ])
            );
            $map[$slug] = $album->id;
        }

        return $map;
    }

    /**
     * Classify an image file into an album and default year based on directory & filename.
     *
     * @param  array<string, int>  $albumMap
     * @return array{0: ?int, 1: int, 2: string}
     */
    protected function classifyPhoto(string $relSubdir, string $filename, array $albumMap): array
    {
        $lowerDir = strtolower(trim($relSubdir, '/\\'));
        $lowerFile = strtolower($filename);

        if (str_contains($lowerDir, 'reading') || str_contains($lowerFile, 'reading')) {
            return [$albumMap['reading-competition'] ?? null, 2025, 'Reading Competition'];
        }

        if (str_contains($lowerDir, 'spelling') || str_contains($lowerFile, 'spelling')) {
            return [$albumMap['spelling-bee'] ?? null, 2025, 'Spelling Bee'];
        }

        if (str_contains($lowerDir, '2026')) {
            return [$albumMap['general'] ?? null, 2026, 'Foundation Milestone'];
        }

        // Root gallery photos: detect year from timestamp or filename
        $year = 2024;
        if (str_contains($lowerFile, '2025') || str_contains($lowerDir, '2025')) {
            $year = 2025;
        } elseif (str_contains($lowerFile, '2026') || str_contains($lowerDir, '2026')) {
            $year = 2026;
        }

        return [$albumMap['general'] ?? null, $year, 'Foundation Archive'];
    }
}
