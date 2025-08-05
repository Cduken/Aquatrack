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
        'reporter_name',
        'reporter_phone',
        'status' // Add this line
    ];

    protected $attributes = [
        'status' => 'pending' // Default value
    ];

    protected $casts = [
        'status' => 'string'
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
        // Access the raw attribute value
        $reporterName = $this->attributes['reporter_name'] ?? null;

        // First try the explicitly provided reporter name
        if ($reporterName) {
            return $reporterName;
        }

        // Then fall back to user name if available
        if ($this->user_id) {
            return $this->user->name;
        }

        // Finally fall back to Guest
        return 'Guest';
    }
}
