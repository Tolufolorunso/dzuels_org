<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerEnquiry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_name',
        'contact_name',
        'email',
        'phone',
        'partnership_type',
        'message',
        'status',
    ];

    /**
     * Scope for pending enquiries.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for recent enquiries.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest();
    }

    /**
     * Human-readable label for partnership type.
     */
    public function getPartnershipTypeLabelAttribute(): string
    {
        return match ($this->partnership_type) {
            'corporate_sponsor' => 'Corporate Sponsor',
            'school_partner' => 'School / Academic Partner',
            'equipment_donor' => 'Equipment / Resource Donor',
            'mentorship_provider' => 'Mentorship Provider',
            'other' => 'Other Partnership',
            default => (string) $this->partnership_type,
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
