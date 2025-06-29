<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipality',
        'province',
        'barangay',
        'purok',
        'description',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone'
    ];

    /**
     * Get all photos for this report
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ReportPhoto::class);
    }

    /**
     * Get the user who created this report
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full address attribute
     */
    public function getFullAddressAttribute(): string
    {
        return "{$this->purok}, {$this->barangay}, {$this->municipality}, {$this->province}";
    }

    /**
     * Get the reporter's name (user name or guest name)
     */
    public function getReporterNameAttribute(): string
    {
        return $this->user_id ? $this->user->name : ($this->guest_name ?? 'Anonymous');
    }
}
