<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'age_band',
        'work_status',
        'skills',
        'status',
    ];

    /**
     * Scope for pending applications.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for recent applications.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest();
    }

    /**
     * Human-readable label for age band.
     */
    public function getAgeBandLabelAttribute(): string
    {
        return match ($this->age_band) {
            'below_18' => 'Under 18',
            '18_to_55' => '18 - 55',
            'above_55' => 'Above 55',
            default => (string) $this->age_band,
        };
    }

    /**
     * Human-readable label for work status.
     */
    public function getWorkStatusLabelAttribute(): string
    {
        return match ($this->work_status) {
            'employed' => 'Employed',
            'self_employed' => 'Self-employed',
            'unemployed' => 'Unemployed',
            'student' => 'Student',
            default => (string) $this->work_status,
        };
    }

    /**
     * Tailwind CSS classes for status badge.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            'reviewed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            'contacted' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            default => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300',
        };
    }
}
