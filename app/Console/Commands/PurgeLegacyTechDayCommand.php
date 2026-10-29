<?php

namespace App\Console\Commands;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use App\Models\Programme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PurgeLegacyTechDayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:purge-legacy-techday
                            {--dry-run : Report files and records without deleting them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge all legacy Tech Day image files and obsolete database records across gallery and media library';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->info('====================================================');
        $this->info(' Dzuels Foundation - Legacy Tech Day Image Purge    ');
        $this->info('====================================================');
        if ($dryRun) {
            $this->warn('DRY RUN MODE: No files or database records will be modified.');
        }

        // 1. Filesystem scan for legacy files
        $optimizedGalleryPath = public_path('front/images/gallery/optimized');
        $filesToDelete = [];

        if (File::isDirectory($optimizedGalleryPath)) {
            $files = File::files($optimizedGalleryPath);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                if (preg_match('/^itd-(2024-|2025-|\d+).*\.webp$/i', $filename)) {
                    $filesToDelete[] = $file->getRealPath();
                }
            }
        }

        $this->line('Discovered <comment>'.count($filesToDelete).'</comment> legacy Tech Day files on disk in gallery/optimized.');

        $deletedFilesCount = 0;
        foreach ($filesToDelete as $filePath) {
            if ($dryRun) {
                $this->line("Would delete file: {$filePath}");
            } else {
                if (@unlink($filePath)) {
                    $deletedFilesCount++;
                } else {
                    $this->error("Failed to delete file: {$filePath}");
                }
            }
        }

        if (! $dryRun) {
            $this->info("Successfully deleted {$deletedFilesCount} legacy files from disk.");
        }

        // 2. Locate obsolete gallery albums
        $itdAlbums = GalleryAlbum::whereIn('slug', ['itd', 'itd-2024', 'itd-2025'])->get();
        $itdAlbumIds = $itdAlbums->pluck('id')->all();

        // 3. Delete legacy GalleryPhotos
        $photosQuery = GalleryPhoto::query()
            ->where(function ($q) use ($itdAlbumIds) {
                if (! empty($itdAlbumIds)) {
                    $q->whereIn('gallery_album_id', $itdAlbumIds);
                }
                $q->orWhere('image_path', 'like', '%/itd-%')
                    ->orWhere('image_path', 'like', '%itd-2024%')
                    ->orWhere('image_path', 'like', '%itd-2025%');
            });

        $photoCount = $photosQuery->count();
        $this->line("Found <comment>{$photoCount}</comment> legacy GalleryPhoto records to remove.");

        if (! $dryRun && $photoCount > 0) {
            $photosQuery->delete();
            $this->info("Deleted {$photoCount} GalleryPhoto records.");
        }

        // 4. Delete legacy MediaItem records
        $mediaQuery = MediaItem::query()
            ->where(function ($q) use ($itdAlbumIds) {
                $q->where('file_path', 'like', '%gallery/optimized/itd-%')
                    ->orWhere('filename', 'like', 'itd-0%')
                    ->orWhere('filename', 'like', 'itd-1%')
                    ->orWhere('filename', 'like', 'itd-2024-%')
                    ->orWhere('filename', 'like', 'itd-2025-%');

                if (! empty($itdAlbumIds)) {
                    $q->orWhere(function ($sub) use ($itdAlbumIds) {
                        $sub->where('source_type', 'gallery')
                            ->whereIn('gallery_album_id', $itdAlbumIds);
                    });
                }
            });

        $mediaCount = $mediaQuery->count();
        $this->line("Found <comment>{$mediaCount}</comment> legacy MediaItem records to remove.");

        if (! $dryRun && $mediaCount > 0) {
            $mediaQuery->delete();
            $this->info("Deleted {$mediaCount} MediaItem records.");
        }

        // 5. Delete obsolete generic "itd" album if present
        $genericAlbum = GalleryAlbum::where('slug', 'itd')->first();
        if ($genericAlbum) {
            if ($dryRun) {
                $this->line("Would delete redundant generic GalleryAlbum: '{$genericAlbum->title}' [{$genericAlbum->slug}]");
            } else {
                $genericAlbum->delete();
                $this->info("Deleted redundant generic GalleryAlbum 'itd'.");
            }
        }

        // 6. Clean legacy cover image from Programme if pointing to deleted file
        $techDayProgramme = Programme::where('slug', 'innovation-and-tech-day')->first();
        if ($techDayProgramme && str_contains($techDayProgramme->cover_image ?? '', 'itd-')) {
            if ($dryRun) {
                $this->line("Would reset legacy cover_image on Programme: '{$techDayProgramme->title}'");
            } else {
                $techDayProgramme->update(['cover_image' => null]);
                $this->info('Reset legacy cover_image on Programme.');
            }
        }

        $this->info('====================================================');
        $this->info(' Legacy Tech Day Purge Complete!                    ');
        $this->info('====================================================');

        return self::SUCCESS;
    }
}
