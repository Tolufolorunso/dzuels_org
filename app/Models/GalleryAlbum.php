<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryAlbum extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'kicker',
        'description',
        'cover_image',
        'sort_order',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the photos belonging to this album.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class, 'gallery_album_id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Scope a query to only include active albums.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to sort albums by order and ID.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Get the resolved public URL for the cover image.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }

            if (str_starts_with($this->cover_image, 'front/') || str_starts_with($this->cover_image, 'uploads/')) {
                return asset($this->cover_image);
            }

            return asset('uploads/gallery/'.$this->cover_image);
        }

        $firstPhoto = $this->photos()->first();
        if ($firstPhoto) {
            return $firstPhoto->image_url;
        }

        return asset('front/images/techday.webp');
    }
}
