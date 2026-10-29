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
        'title',
        'caption',
        'alt_text',
        'source_type',
        'year',
        'gallery_album_id',
        'gallery_photo_id',
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
            'year' => 'integer',
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
     * Scope for customizable sorting.
     */
    public function scopeOrdered(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'year_desc' => $query->orderByRaw('year IS NULL, year DESC')->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
            'year_asc' => $query->orderByRaw('year IS NULL, year ASC')->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'name_asc' => $query->orderBy('filename', 'asc'),
            'name_desc' => $query->orderBy('filename', 'desc'),
            'size_asc' => $query->orderBy('file_size', 'asc'),
            'size_desc' => $query->orderBy('file_size', 'desc'),
            default => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
        };
    }
}
