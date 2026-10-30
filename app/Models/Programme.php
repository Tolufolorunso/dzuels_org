<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Programme extends Model
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
        'summary',
        'body',
        'cover_image',
        'partner_name',
        'gallery_album_id',
        'gallery_photos',
        'is_featured',
        'is_active',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'gallery_album_id' => 'integer',
            'gallery_photos' => 'array',
        ];
    }

    /**
     * Scope a query to only include active programmes.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured programmes.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to sort programmes by order and ID.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Get the gallery album linked to the programme.
     */
    public function galleryAlbum(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    /**
     * Get the consolidated list of gallery photos for this programme.
     * Returns a Collection of photo objects (each with image_path, caption, alt_text).
     *
     * @return Collection<int, object{image_path: string, caption: ?string, alt_text: string}>
     */
    public function getResolvedGalleryPhotos(): Collection
    {
        $photos = collect();

        if (! empty($this->gallery_photos) && is_array($this->gallery_photos)) {
            foreach ($this->gallery_photos as $item) {
                if (is_string($item)) {
                    $photos->push((object) [
                        'image_path' => $item,
                        'caption' => null,
                        'alt_text' => $this->title,
                    ]);
                } elseif (is_array($item) && ! empty($item['path'])) {
                    $photos->push((object) [
                        'image_path' => $item['path'],
                        'caption' => $item['caption'] ?? null,
                        'alt_text' => $item['alt_text'] ?? $this->title,
                    ]);
                }
            }
        }

        if ($photos->isEmpty() && $this->gallery_album_id && $this->galleryAlbum) {
            foreach ($this->galleryAlbum->photos as $photo) {
                $photos->push((object) [
                    'image_path' => $photo->image_path,
                    'caption' => $photo->caption,
                    'alt_text' => $photo->alt_text ?: $this->title,
                ]);
            }
        }

        return $photos;
    }

    /**
     * Get the resolved public URL for the cover image.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if (! $this->cover_image) {
            return asset('front/images/dzuelsCollage.webp');
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        if (str_starts_with($this->cover_image, 'front/') || str_starts_with($this->cover_image, 'uploads/')) {
            if (file_exists(public_path($this->cover_image))) {
                return asset($this->cover_image);
            }
        }

        if ($this->cover_image && file_exists(public_path('uploads/programmes/'.$this->cover_image))) {
            return asset('uploads/programmes/'.$this->cover_image);
        }

        return asset('front/images/dzuelsCollage.webp');
    }
}
