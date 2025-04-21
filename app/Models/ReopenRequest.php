<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReopenRequest extends Model
{
    protected $fillable = [
        'student_id',
        'supervisor_id',
        'reopened_date',
        'expires_at',
        'message',
        'status',
    ];

    protected $casts = [
        'reopened_date' => 'date',
        'expires_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'PENDING')
                     ->where('expires_at', '>', now());
    }
    public function scopeExpired($query)
    {
        return $query->where('status', 'PENDING')
                     ->where('expires_at', '<=', now());
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', 'CANCELLED');
    }
}
