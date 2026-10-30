<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'filename',
        'file_path',
        'file_size',
        'mime_type',
        'width',
        'height',
        'taken_at',
        'color_space',
        'title',
        'caption',
        'alt_text',
        'source_type',
        'year',
        'is_public',
        'gallery_album_id',
        'gallery_photo_id',
    ];

    /**
     * The accessors to append to the model's array and JSON form.
     *
     * @var list<string>
     */
    protected $appends = [
        'image_url',
        'formatted_size',
        'dimensions',
        'aspect_ratio',
        'formatted_taken_at',
        'capture_date_formatted',
        'color_space_badge',
        'formatted_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'taken_at' => 'datetime',
            'year' => 'integer',
            'is_public' => 'boolean',
            'gallery_album_id' => 'integer',
        ];
    }

    /**
     * Associated gallery album / collection if linked.
     */
    public function galleryAlbum(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    /**
     * Associated gallery photo if linked.
     */
    public function galleryPhoto(): BelongsTo
    {
        return $this->belongsTo(GalleryPhoto::class, 'gallery_photo_id');
    }

    /**
     * Publicly accessible web URL for this media item.
     */
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        return asset($this->file_path);
    }

    /**
     * Human-friendly formatted file size (e.g. 142 KB).
     */
    public function getFormattedSizeAttribute(): string
    {
        if (! $this->file_size) {
            return '0 KB';
        }

        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 2).' MB';
        }

        return number_format($this->file_size / 1024, 1).' KB';
    }

    /**
     * Formatted pixel dimensions string (e.g. "1920 × 1080").
     */
    public function getDimensionsAttribute(): string
    {
        if ($this->width && $this->height) {
            return "{$this->width} × {$this->height}";
        }

        return 'Auto';
    }

    /**
     * Approximate aspect ratio (e.g. 16:9, 4:3, 1:1, 3:2).
     */
    public function getAspectRatioAttribute(): string
    {
        if (! $this->width || ! $this->height) {
            return 'Auto';
        }

        $ratio = $this->width / $this->height;

        if (abs($ratio - 16 / 9) < 0.08) {
            return '16:9';
        }
        if (abs($ratio - 4 / 3) < 0.08) {
            return '4:3';
        }
        if (abs($ratio - 3 / 2) < 0.08) {
            return '3:2';
        }
        if (abs($ratio - 1.0) < 0.05) {
            return '1:1';
        }
        if (abs($ratio - 9 / 16) < 0.08) {
            return '9:16 (Portrait)';
        }
        if (abs($ratio - 3 / 4) < 0.08) {
            return '3:4 (Portrait)';
        }
        if (abs($ratio - 2 / 3) < 0.08) {
            return '2:3 (Portrait)';
        }

        return number_format($ratio, 2).':1';
    }

    /**
     * Formatted capture date and time (e.g. "Oct 3, 2026, 10:15 AM").
     */
    public function getFormattedTakenAtAttribute(): ?string
    {
        return $this->taken_at ? $this->taken_at->format('M d, Y, h:i A') : null;
    }

    /**
     * Short display capture date (e.g. "Oct 3, 2026").
     */
    public function getCaptureDateFormattedAttribute(): string
    {
        return $this->taken_at ? $this->taken_at->format('M d, Y') : 'Unknown';
    }

    /**
     * Resolved color space badge string (default: sRGB).
     */
    public function getColorSpaceBadgeAttribute(): string
    {
        return $this->color_space ?: 'sRGB';
    }

    /**
     * Formatted display date for the media manager UI.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : 'Unknown';
    }

    /**
     * Scope for multi-parameter search (filename, title, caption, alt_text).
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $search = '%'.trim($term).'%';

        return $query->where(function (Builder $q) use ($search) {
            $q->where('filename', 'like', $search)
                ->orWhere('title', 'like', $search)
                ->orWhere('caption', 'like', $search)
                ->orWhere('alt_text', 'like', $search)
                ->orWhere('file_path', 'like', $search);
        });
    }

    /**
     * Scope for filtering by date or quick date ranges.
     */
    public function scopeByDateFilter(Builder $query, ?string $dateFilter): Builder
    {
        if (blank($dateFilter) || $dateFilter === 'all') {
            return $query;
        }

        // Check if filter is a specific YYYY-MM-DD date
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFilter)) {
            return $query->whereDate('created_at', $dateFilter);
        }

        return match ($dateFilter) {
            'today' => $query->whereDate('created_at', Carbon::today()),
            'this_week' => $query->where('created_at', '>=', Carbon::now()->startOfWeek()),
            'this_month' => $query->where('created_at', '>=', Carbon::now()->startOfMonth()),
            'past_year' => $query->where('created_at', '>=', Carbon::now()->subYear()),
            default => $query,
        };
    }

    /**
     * Scope for filtering by source category or dynamic collection.
     */
    public function scopeBySourceType(Builder $query, ?string $source): Builder
    {
        if (blank($source) || $source === 'all') {
            return $query;
        }

        if ($source === 'site' || $source === 'site-assets') {
            return $query->where(function (Builder $q) {
                $q->whereIn('source_type', ['general', 'cms', 'partner', 'programme', 'team'])
                    ->whereNull('gallery_album_id');
            });
        }

        if ($source === 'gallery') {
            return $query->where(function (Builder $q) {
                $q->where('source_type', 'gallery')
                    ->orWhereNotNull('gallery_album_id');
            });
        }

        return $query->where(function (Builder $q) use ($source) {
            $q->where('source_type', $source)
                ->orWhere('gallery_album_id', $source)
                ->orWhereHas('galleryAlbum', function (Builder $albumQuery) use ($source) {
                    $albumQuery->where('slug', $source);
                });
        });
    }

    /**
     * Scope for filtering by year.
     */
    public function scopeByYear(Builder $query, mixed $year): Builder
    {
        if (blank($year) || $year === 'all') {
            return $query;
        }

        return $query->where('year', (int) $year);
    }

    /**
     * Scope to only include items marked as publicly visible in gallery.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to only include archive items (hidden from public gallery).
     */
    public function scopeArchive(Builder $query): Builder
    {
        return $query->where('is_public', false);
    }

    /**
     * Scope to filter by public gallery visibility.
     */
    public function scopeByVisibility(Builder $query, ?string $visibility): Builder
    {
        if (blank($visibility) || $visibility === 'all') {
            return $query;
        }

        if ($visibility === 'public') {
            return $query->where('is_public', true);
        }

        if ($visibility === 'archive') {
            return $query->where('is_public', false);
        }

        return $query;
    }

    /**
     * Scope for customizable sorting.
     */
    public function scopeOrdered(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'captured_desc' => $query->orderByRaw('taken_at IS NULL, taken_at DESC')->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
            'captured_asc' => $query->orderByRaw('taken_at IS NULL, taken_at ASC')->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'year_desc' => $query->orderByRaw('year IS NULL, year DESC')->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
            'year_asc' => $query->orderByRaw('year IS NULL, year ASC')->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'name_asc' => $query->orderBy('filename', 'asc'),
            'name_desc' => $query->orderBy('filename', 'desc'),
            'size_asc' => $query->orderBy('file_size', 'asc'),
            'size_desc' => $query->orderBy('file_size', 'desc'),
            'dimensions_desc' => $query->orderByRaw('(COALESCE(width, 0) * COALESCE(height, 0)) DESC')->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
            'dimensions_asc' => $query->orderByRaw('(COALESCE(width, 0) * COALESCE(height, 0)) ASC')->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            default => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
        };
    }
}
