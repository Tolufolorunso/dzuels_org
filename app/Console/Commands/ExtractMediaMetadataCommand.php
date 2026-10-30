<?php

namespace App\Console\Commands;

use App\Models\MediaItem;
use App\Services\MediaMetadataExtractor;
use Illuminate\Console\Command;

class ExtractMediaMetadataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:extract-metadata {--force : Re-extract metadata for all items even if taken_at or color_space is already present} {--limit= : Maximum number of media items to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan existing media library assets on disk and backfill EXIF metadata (taken_at, color_space, dimensions, file_size)';

    /**
     * Execute the console command.
     */
    public function handle(MediaMetadataExtractor $extractor): int
    {
        $force = (bool) $this->option('force');

        $query = MediaItem::query();
        if (! $force) {
            $query->where(function ($q) {
                $q->whereNull('taken_at')
                    ->orWhereNull('color_space')
                    ->orWhereNull('width')
                    ->orWhereNull('height');
            });
        }

        if ($limit = $this->option('limit')) {
            $query->limit((int) $limit);
        }

        $items = $query->get();
        $total = $items->count();

        if ($total === 0) {
            $this->info('All media items already have metadata extracted.');

            return self::SUCCESS;
        }

        $this->info("Extracting metadata for {$total} media items...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updatedCount = 0;
        $exifFoundCount = 0;

        foreach ($items as $item) {
            $meta = $extractor->extract($item->file_path);

            $updates = [
                'color_space' => $meta['color_space'],
            ];

            if ($meta['taken_at']) {
                $updates['taken_at'] = $meta['taken_at'];
                $exifFoundCount++;
            }

            if (! $item->width && $meta['width']) {
                $updates['width'] = $meta['width'];
            }
            if (! $item->height && $meta['height']) {
                $updates['height'] = $meta['height'];
            }
            if (! $item->file_size && $meta['file_size']) {
                $updates['file_size'] = $meta['file_size'];
            }

            $item->update($updates);
            $updatedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully updated {$updatedCount} media items ({$exifFoundCount} with explicit EXIF capture timestamps).");

        return self::SUCCESS;
    }
}
