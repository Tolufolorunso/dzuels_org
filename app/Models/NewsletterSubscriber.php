<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include active subscribers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('unsubscribed_at');
    }

    /**
     * Scope a query to only include unsubscribed members.
     */
    public function scopeUnsubscribed(Builder $query): Builder
    {
        return $query->whereNotNull('unsubscribed_at');
    }

    /**
     * Determine if the subscriber is currently active.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->unsubscribed_at === null;
    }

    /**
     * Get the direct tokenized unsubscribe URL.
     */
    public function getUnsubscribeUrlAttribute(): string
    {
        if (! $this->unsubscribe_token) {
            return url('/newsletter/unsubscribe');
        }

        return url('/newsletter/unsubscribe/'.$this->unsubscribe_token);
    }
}
