<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Academic extends Model
{
    protected $fillable = [
        'academic_year',    // Keep existing field
        'semester',         // Keep existing field
        'ay_default',       // Keep existing field
        'status',          // Keep existing field
        'description',      // Keep existing field
        'start_date',      // Add new field for semester date range
        'end_date',        // Add new field for semester date range
    ];

    protected $casts = [
        'ay_default' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationship with courses for this academic period
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // Relationship with sections for this academic period
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    // Relationship with deployments for this academic period
    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }

    // Accessor for formatted academic year display
    public function getFormattedAcademicYearAttribute(): string
    {
        return "{$this->academic_year} - {$this->semester} Semester";
    }

    // Scope to get current academic period
    public function scopeCurrent($query)
    {
        return $query->where('ay_default', true);
    }

    // Scope to get active academic periods
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    // Check if academic period is current
    public function isCurrent(): bool
    {
        return $this->ay_default === true;
    }
}