<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_key',
        'section_key',
        'headline',
        'subheadline',
        'body_text',
        'image_path',
        'button_text',
        'button_url',
        'meta_data',
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
            'meta_data' => 'array',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope a query to only include sections for a given page.
     */
    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page_key', $page)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Retrieve all sections for a page, keyed by section_key.
     *
     * @return Collection<string, self>
     */
    public static function getPageSections(string $page): Collection
    {
        try {
            return static::forPage($page)->get()->keyBy('section_key');
        } catch (\Throwable) {
            return new Collection;
        }
    }

    /**
     * Retrieve a specific section model or null.
     */
    public static function getSection(string $page, string $section): ?self
    {
        try {
            return static::where('page_key', $page)->where('section_key', $section)->first();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Get the publicly accessible URL for the section image.
     */
    public function getImageUrl(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'front/')) {
            return asset($this->image_path);
        }

        if (str_starts_with($this->image_path, 'uploads/')) {
            return asset($this->image_path);
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
