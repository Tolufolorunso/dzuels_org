<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhoto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'gallery_album_id',
        'image_path',
        'caption',
        'alt_text',
        'year',
        'is_public',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope to only include photos marked as publicly visible.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to only include archive photos (hidden from public gallery).
     */
    public function scopeArchive(Builder $query): Builder
    {
        return $query->where('is_public', false);
    }

    /**
     * Get the album this photo belongs to.
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    /**
     * Get the resolved public URL for the photo.
     */
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'front/') || str_starts_with($this->image_path, 'uploads/')) {
            return asset($this->image_path);
        }

        return asset('uploads/gallery/'.$this->image_path);
    }

    /**
     * Get the formatted display label including year.
     */
    public function getDisplayLabelAttribute(): string
    {
        $label = $this->caption ?: ($this->album?->title ?? 'Gallery Photo');
        if ($this->year && ! str_contains($label, (string) $this->year)) {
            return $label.' '.$this->year;
        }

        return $label;
    }
}
