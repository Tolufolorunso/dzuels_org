<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
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
        'description',
        'event_date',
        'end_date',
        'venue',
        'theme',
        'objective',
        'banner_image',
        'schedule',
        'is_registration_open',
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
            'event_date' => 'datetime',
            'end_date' => 'datetime',
            'schedule' => 'array',
            'is_registration_open' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the sponsors for the event.
     */
    public function sponsors(): HasMany
    {
        return $this->hasMany(EventSponsor::class)->ordered();
    }

    /**
     * Scope a query to only include active events.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(function (Builder $sub) {
            $sub->where('event_date', '>=', now()->startOfDay())
                ->orWhereNull('event_date');
        });
    }

    /**
     * Scope a query to sort events by sort_order and event_date.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('event_date', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Get the resolved public URL for the banner image.
     */
    public function getBannerImageUrlAttribute(): string
    {
        if (! $this->banner_image) {
            return asset('front/images/techday.webp');
        }

        if (str_starts_with($this->banner_image, 'http://') || str_starts_with($this->banner_image, 'https://')) {
            return $this->banner_image;
        }

        if (str_starts_with($this->banner_image, 'front/') || str_starts_with($this->banner_image, 'uploads/')) {
            return asset($this->banner_image);
        }

        return asset('uploads/events/'.$this->banner_image);
    }

    /**
     * Determine if the event has already concluded.
     */
    public function getIsPastAttribute(): bool
    {
        return $this->event_date ? ($this->event_date->isPast() && ! $this->event_date->isToday()) : false;
    }

    /**
     * Determine if the event is in the future.
     */
    public function getIsFutureAttribute(): bool
    {
        return $this->event_date ? ($this->event_date->isFuture() && ! $this->event_date->isToday()) : false;
    }

    /**
     * Determine if the event is happening today.
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->event_date ? $this->event_date->isToday() : false;
    }

    /**
     * Get a contextual, human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->is_today) {
            return 'Happening Today';
        }

        if ($this->is_future) {
            return str_contains($this->slug, 'tech-day') || str_contains($this->slug, 'itd')
                ? 'Upcoming Flagship'
                : 'Upcoming Gathering';
        }

        if ($this->is_past) {
            return match ($this->slug) {
                'ijero-tech-day-2024' => 'Inaugural Edition (Concluded)',
                'ijero-tech-day-2025' => '2nd Edition (Concluded)',
                'ijero-tech-day-2026' => 'Concluded Edition',
                default => 'Concluded Event',
            };
        }

        return 'Scheduled Event';
    }

    /**
     * Get a contextual badge string for edition switchers.
     */
    public function getEditionBadgeAttribute(): string
    {
        $year = $this->event_date?->year;

        if ($this->is_future) {
            return $year ? "{$year} • Upcoming Flagship" : 'Upcoming Flagship';
        }

        if ($this->is_today) {
            return $year ? "{$year} • Happening Today" : 'Happening Today';
        }

        return match ($this->slug) {
            'ijero-tech-day-2024' => '2024 • Inaugural',
            'ijero-tech-day-2025' => '2025 • 2nd Edition',
            'ijero-tech-day-2026' => '2026 • Concluded',
            default => $year ? "{$year} • Concluded" : 'Concluded',
        };
    }

    /**
     * Format the date nicely for displays.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->event_date ? $this->event_date->format('F j, Y') : 'Date to be announced';
    }

    /**
     * Format the time for display.
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->event_date ? $this->event_date->format('g:i A') : 'TBA';
    }
}
